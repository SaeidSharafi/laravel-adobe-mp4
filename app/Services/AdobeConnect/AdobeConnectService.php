<?php

namespace App\Services\AdobeConnect;

use App\Models\AdobeConnectRecording;
use App\Services\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdobeConnectService
{

    private AdobeConnectSettingService $adobeConnectSettingService;
    private AdobeConnectClientService $adobeConnectClientService;

    public function __construct()
    {
        $this->adobeConnectSettingService = app()->make(AdobeConnectSettingService::class);
        $this->adobeConnectClientService = app()->make(AdobeConnectClientService::class);
    }

    public function getRecordings() : void
    {

        $from_date = '2023-01-01';
        $to_date = '2024-01-01';
        $request = 'report-bulk-objects&filter-icon=archive&filter-gt-date-created='.$from_date;
        if ($to_date) {
            $request .= '&filter-lt-date-created='.$to_date;
        }
        $api_rqst = $this->adobeConnectClientService->makeRequest($request);

        if (config('app.debug')) {
            Log::info("report-bulk-objects created after $from_date", $api_rqst);
        }
        $total_records = null;
        if (array_key_exists('row', $api_rqst['report-bulk-objects'])) {
            $total_records = $api_rqst['report-bulk-objects']['row'];
        }

        if (!$total_records) {
            return;
        }

        Log::info('total_records:', $total_records);

        if (is_array($total_records[0])) {
            for ($i = 0, $iMax = count($total_records); $i < $iMax; $i++) {
                if (!isset($total_records[$i]['@attributes']['sco-id'])) {
                    continue;
                }
                $sco_id_url = $total_records[$i]['@attributes']['sco-id'];
                $sco_id = $sco_id_url;
                $folder = $this->getFolderInfo($sco_id);
                $folder_sco = $folder['sco'];
                $meeting_name = $folder['name'];
                $meeting_url = $folder['url'];
                // SCreen out recordings that are not from classes or the CAE Tech talk
                $skip = false;
                $downloads = explode('|', '*');
                foreach ($downloads as $down) {
                    if (strpos($meeting_url, $down) > -1) {
                        $skip = true;
                    }
                }

                $duration = 0;
                if (array_key_exists('duration', $folder)) {
                    $duration = ($folder['duration']);
                }
                $duration = abs($duration);

                if ($duration < 600) {
                    $skip = true;
                }
                if ($skip) {
                    Log::info($meeting_url.' does not fit criteria. Skipped.');
                    continue;
                }

                $date_created = '';
                if (isset($total_records[$i]['date-end'])) {
                    $date_created = $total_records[$i]['date-end'];
                }
                if (isset($total_records[$i]['date-created'])) {
                    $date_created = $total_records[$i]['date-created'];
                }

                $url = $this->adobeConnectSettingService->getUrl().$total_records[$i]['url'].'?proto=true';
                $url = str_replace('com//', 'com/', $url); // get rid of double slashes in wrong place

                //
                // let's get the true duration from a bunch of other API calls
                //

                Log::info('Folder_sco: '.$folder_sco.' sco: '.$sco_id.' duration: '.$duration);

                $hr = floor($duration / 3600);
                $min = floor(($duration % 3600) / 60);
                if ($min < 10) {
                    $min = '0'.$min;
                }

                $rc_name = $total_records[$i]['name'];

                $ans = 'false';
                if ($duration > 15000) {
                    $ans = 'true';
                }
                Log::info("$rc_name Duration: $duration = $hr:$min duration>15000: $ans");

                $folder_name = substr($meeting_url, 1, -1);

                Log::info('course: '.$folder_name);

                AdobeConnectRecording::upsert([
                    'scoid'         => $sco_id_url,
                    'foldername'    => $folder_name,
                    'url'           => $url,
                    'datecreated'   => $date_created,
                    'meetingurl'    => $meeting_url,
                    'meetingname'   => $meeting_name,
                    'recordingname' => $rc_name,
                    'duration'      => $duration,
                    ],
                    ['scoid'],
                    [
                        'duration',
                        'meetingname',
                        'recordingname',
                    ]
                );


            }
        } else {
            $sco_id_url = $total_records['@attributes']['sco-id'];
            $sco_id = $sco_id_url;
            $rc_name = $total_records['name'];
            $date_created = now();
            if (isset($total_records['date-end'])) {
                $date_created = $total_records['date-end'];
            }
            if (isset($total_records['date-created'])) {
                $date_created = $total_records['date-created'];
            }

            $folder = $this->getFolderInfo($sco_id);
            $folder_sco = $folder['sco'];
            $meeting_name = $folder['name'];
            $meeting_url = $folder['url'];

            Log::info('Folder Sco-id: '.$folder_sco);

            $duration = $this->getDuration($sco_id);
            $duration = abs($duration);
            $hr = floor($duration / 3600);
            $min = floor(($duration % 3600) / 60);
            if ($min < 10) {
                $min = '0'.$min;
            }

            Log::info('Duration (adjusted): '.$duration.' = '.$hr.':'.$min);

            $url = $this->adobeConnectSettingService->getUrl().$total_records['url'].'?proto=true';

            $folder_name = substr($meeting_url, 1, -1);
            Log::info('course: '.$folder_name);
            if (strpos($meeting_url, '_20') > 5
                || strpos($meeting_url, 'cae') > -1
            ) { // do not make recordings of NON classes.

                AdobeConnectRecording::upsert([
                    'scoid'         => $sco_id_url,
                    'foldername'    => $folder_name,
                    'url'           => $url,
                    'datecreated'   => $date_created,
                    'meetingurl'    => $meeting_url,
                    'meetingname'   => $meeting_name,
                    'recordingname' => $rc_name,
                    'duration'      => $duration,
                ],
                    ['scoid'],
                    [
                        'duration',
                        'meetingname',
                        'recordingname',
                    ]
                );

            }
        }

        DB::statement('INSERT INTO adobe_connect_recording_queues (scoid)
                    SELECT scoid FROM adobe_connect_recordings
                    WHERE scoid not in (SELECT scoid FROM adobe_connect_recording_queues)');

    }

    public function clearChar($string) : string
    {

        // Adobe automatically replaces reserved characters with an underscore when saving
        // file names. We need to replace them in the stored recording name, or we cannot
        // locate the files later
        // We also need to remove apostrophes from the names since they mess with SQL queries
        $res_chars = "\/:*?<>'|".chr(34);
        for ($charNo = 0, $charNoMax = strlen($res_chars); $charNo < $charNoMax; $charNo++) {
            $ResChar = substr($res_chars, $charNo, 1);
            $string = str_replace($ResChar, '_', $string);
        }
        return preg_replace('/[^A-Za-z0-9\-]/', '_', $string);
    }

    private function getFolderID($sco)
    {
        global $acc;
        $scos = $acc->makeRequest('sco-info&sco-id='.$sco);
        $folder_scoData = $scos['sco'];
        return $folder_scoData['@attributes']['folder-id'];
    }

    private function getFolderInfo($sco_id): array
    {
        $icon_type = '';
        Log::info('Start getFolderInfo('.$sco_id.')');
        //if ($debug) print('<h3>Start getFolderInfo('.$sco_id.')</h3>');
        $ret = array();
        $ret['name'] = '';
        $ret['sco'] = $sco_id;
        $ret['url'] = '';
        $ret['duration'] = 0;

        while ($icon_type !== 'meeting' && $icon_type !== 'archive') {
            //var_dump($icon_type);
            $ret['sco'] = $sco_id; // this has to be before it is processed, since we are using folder-id
            $results = $this->adobeConnectClientService->makeRequest('sco-info&sco-id='.$sco_id);
            //var_dump($results);
            if ($results['status']['@attributes']['code'] !== 'ok') {
                Log::info('No Data');
                print_r($results);
                break;
                // continue;
            }

            $sco = $results['sco'];
            Log::info('action=sco-info&sco-id='.$sco_id,$results);
            if (isset($sco['@attributes']['folder-id'])) {
                $sco_id = $sco['@attributes']['folder-id'];
            }
            if (isset($sco['@attributes']['icon'])) {
                $icon_type = $sco['@attributes']['icon'];
            }
            if (isset($sco['name'])) {
                $ret['name'] = $sco['name'];
            }
            if (isset($sco['url-path'])) {
                $ret['url'] = $sco['url-path'];
            }
            if (isset($results['sco-calculated-values'])) {
                $ret['duration'] = ceil(($results['sco-calculated-values']['duration']) / 1000);
            }
            Log::info('getFolderInfo temp sco: '.$sco_id.' meeting name: '.$ret['name'].' url: '.$ret['url'].
                ' meeting sco: '.$ret['sco']);
            Log::info('<p> temp sco: '.$sco_id.' meeting name: '.$ret['name'].' url: '.$ret['url'].' meeting sco: '.
                $ret['sco'].'</p>');

        }
        //var_dump($ret);
        if ($icon_type === 'meeting' && $ret['sco'] !== '') {
            while (isset($sco['@attributes']['folder-id']) && $sco['@attributes']['folder-id'] != '1141159112') {
                $fold = $sco['@attributes']['folder-id'];
                $scos = $this->adobeConnectClientService->makeRequest('sco-info&sco-id='.$fold);
                if (!isset($scos['sco'])) {
                    break;
                }
                $sco = $scos['sco'];
            }

        }
        $course = $ret;
        //var_dump($course);
        $course['course_id'] = $ret['url'];
        //canvasInfo($ret['url']);  Removing Canvas calls from this distribution
        $ret = $course;
        //if ($debug && is_array($course)) foreach ($course as $key=>$val)
        //{Log::info($ret['url']. ' -- '. $key.': '.$val);
        //}
        Log::info('getFolderInfo FINAL sco: '.$sco_id.' meeting name: '.$ret['name'].' url: '.$ret['url'].
            ' meeting sco: '.
            $ret['sco']);
        Log::info('<p><strong> FINAL sco: '.$sco_id.' meeting name: '.$ret['name'].' url: '.$ret['url'].
            ' meeting sco: '.
            $ret['sco'].'</strong></p>');
        return $ret;
    }

    private function getDuration($seconds) : string
    {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t / 3600), ($t / 60 % 60), $t % 60);
    }

}
