<template>
    <div class="border border-gray-700 rounded-lg p-3">
        <validation-errors class="mb-4"/>

        <div v-show="upload && upload.dropActive" class="drop-active">
            <h3>رها کنید</h3>
        </div>
        <div class="upload">
            <div class="grid lg:grid-cols-12 sm:grid-cols-4 grid-cols-2">
                <div v-if="!computedValue.length" class="col-span-full">
                    <div class="text-center p-5">
                        <h4>برای آپلود فایل ها را در این بخش رها کنید<br/>یا</h4>
                        <label :for="input_name"
                               class="cursor-pointer whitespace-nowrap focus:outline-none transition-colors focus:ring duration-150 border rounded-full border-blue-600 dark:border-blue-500 ring-blue-300 dark:ring-blue-700 bg-blue-600 dark:bg-blue-500 text-white hover:bg-blue-700 hover:border-blue-700 hover:dark:bg-blue-600 hover:dark:border-blue-600 text-sm px-3 py-1">انتخاب کنید</label>
                    </div>
                </div>
                <div v-for="(file, index) in computedValue" :key="file.id">
                    <div class="flex flex-col justify-between aspect-square w-full border border-gray-400">
                        <div class="flex items-center justify-center">
                            <img class="td-image-thumb" v-if="file.thumb" :src="file.thumb"/>
                            <BaseIcon :path="mdiFileExcel" :size="64" w="w-9" h="w-9" v-else></BaseIcon>
                        </div>

                        <div class=" text-center">
                            <div class="text-2xs"> {{ file.name }}</div>
                            <div class="progress" v-if="file.active || file.progress !== '0.00'">
                                <div :class="{'progress-bar': true, 'progress-bar-striped': true, 'bg-danger': file.error, 'progress-bar-animated': file.active}" role="progressbar"
                                     :style="{width: file.progress + '%'}">{{ file.progress }}%
                                </div>
                            </div>
                            <div v-if="file.error">{{ file.error }}</div>
                            <div v-else-if="file.success">success</div>
                            <div v-else-if="file.active">active</div>
                            <div v-else></div>
                            <BaseButton color="danger" small label="Abort" type="button" v-if="file.active" @click.prevent="upload.update(file, {active: false})"></BaseButton>
                            <BaseButton color="danger" :icon="mdiTrashCan" small type="button" @click.prevent="upload.remove(file)">

                            </BaseButton>
                        </div>

                    </div>
                </div>
            </div>
            <div class="">
                <div class="btn-group">
                    <file-upload
                        class="btn btn-primary dropdown-toggle"
                        :post-action="postAction"
                        :put-action="putAction"
                        :extensions="computedExtention"
                        :accept="computedAccept"
                        :multiple="multiple"
                        :directory="directory"
                        :create-directory="createDirectory"
                        :size="size || 0"
                        :thread="thread < 1 ? 1 : (thread > 5 ? 5 : thread)"
                        :headers="headers"
                        :data="data"
                        :drop="true"
                        :drop-active="true"
                        :drop-directory="dropDirectory"
                        :add-index="addIndex"
                        v-model="computedValue"
                        @input-filter="inputFilter"
                        @input-file="inputFile"
                        ref="upload">
                        <i class="fa fa-plus"></i>
                    </file-upload>
                </div>
            </div>
        </div>

    </div>
</template>


<script setup>

import FileUpload from 'vue-upload-component'
import {computed, ref} from "vue";
import BaseButton from "@/Components/Form/BaseButton.vue";
import BaseIcon from "@/Components/Layout/BaseIcon.vue";
import {mdiFileExcel, mdiTrashCan} from "@mdi/js";
import ValidationErrors from "@/Components/Form/ValidationErrors.vue";

const upload = ref(null);
const input_name = 'file';
const addData = {
    show: false,
    name: '',
    type: '',
    content: '',
}
const editFile = {
    show: false,
    name: '',
}
const props = defineProps({
    modelValue: {
        type: Array,
        default: null,
    },
    accept: {
        type: String,
        default: 'image/png,image/gif,image/jpeg,image/webp'
    },
    extensions: {
        type: [String, Array],
        default: 'gif,jpg,jpeg,png,webp'
    },
    fileType: {
        type: String,
        default: 'image',
        validator: (value) => ["image", "csv", "custom"].includes(value),

    },
    // extensions: ['gif', 'jpg', 'jpeg','png', 'webp'],
    // extensions: /\.(gif|jpe?g|png|webp)$/i,
    minSize: 1024,
    size: 1024 * 1024 * 10,
    multiple: true,
    directory: false,
    drop: true,
    dropDirectory: true,
    createDirectory: false,
    addIndex: false,
    thread: 3,

    postAction: '/upload/post',
    putAction: '/upload/put',
    headers: {
        'X-Csrf-Token': 'xxxx',
    },
    data: {
        '_csrf_token': 'xxxxxx',
    },
    autoCompress: 1024 * 1024,
    uploadAuto: false,
    isOption: false,

});
const emit = defineEmits(["update:modelValue", "onDateChange"]);

const computedAccept = computed(() => {
    switch (props.fileType) {
        case 'image':
            return 'image/png,image/gif,image/jpeg,image/webp';
        case 'csv':
            return '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel';
        default:
            return props.accept;
    }
});
const computedExtention = computed(() => {
    switch (props.fileType) {
        case 'image':
            return 'gif,jpg,jpeg,png,webp';
        case 'csv':
            return 'csv,xls,xlsx,odc';
        default:
            return props.extensions;
    }
});

const computedValue = computed({
    get: () => {
        return props.modelValue
    },
    set: (value) => {
        emit("update:modelValue", value)
    },
});

function inputFilter(newFile, oldFile, prevent) {
    if (newFile && !oldFile) {
        // Before adding a file
        // Filter system files or hide files
        if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
            return prevent()
        }
        // Filter php html js file
        if (/\.(php5?|html?|jsx?)$/i.test(newFile.name) && newFile.type !== "text/directory") {
            return prevent()
        }
    }
    if (newFile && newFile.error === "" && newFile.file && (!oldFile || newFile.file !== oldFile.file)) {
        // Create a blob field
        newFile.blob = ''
        let URL = (window.URL || window.webkitURL)
        if (URL) {
            newFile.blob = URL.createObjectURL(newFile.file)
        }
        // Thumbnails
        newFile.thumb = ''
        if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
            newFile.thumb = newFile.blob
        }
    }
    // image size
    // image 尺寸
    // if (newFile && newFile.error === '' && newFile.type.substr(0, 6) === "image/" && newFile.blob && (!oldFile || newFile.blob !== oldFile.blob)) {
    //     newFile.error = 'image parsing'
    //     let img = new Image();
    //     img.onload = () => {
    //         upload.update(newFile, {error: '', height: img.height, width: img.width})
    //     }
    //     img.οnerrοr = (e) => {
    //         upload.update(newFile, {error: 'parsing image size'})
    //     }
    //     img.src = newFile.blob
    // }
}

// add, update, remove File Event
function inputFile(newFile, oldFile) {
    if (newFile && oldFile) {
        // update
        if (newFile.active && !oldFile.active) {
            // beforeSend
            // min size
            if (newFile.size >= 0 && props.minSize > 0 && newFile.size < props.minSize && newFile.type !== "text/directory") {
                upload.update(newFile, {error: 'size'})
            }
        }
        if (newFile.progress !== oldFile.progress) {
            // progress
        }
        if (newFile.error && !oldFile.error) {
            // error
        }
        if (newFile.success && !oldFile.success) {
            // success
        }
    }
    if (!newFile && oldFile) {
        // remove
        if (oldFile.success && oldFile.response.id) {
            // $.ajax({
            //   type: 'DELETE',
            //   url: '/upload/delete?id=' + oldFile.response.id,
            // })
        }
    }
    // Automatically activate upload
    if (Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
        if (props.uploadAuto && !props.upload.active) {
            upload.active = true
        }
    }
}

const alert = function (message) {
    alert(message)
}

function onEditFileShow(file) {
    props.editFile = {...file, show: true}
    upload.update(file, {error: 'edit'})
}

function onEditorFile() {
    if (!upload.features.html5) {
        alert('Your browser does not support')
        props.editFile.show = false
        return
    }
    let data = {
        name: editFile.name,
        error: '',
    }
    if (editFile.cropper) {
        let binStr = atob(editFile.cropper.getCroppedCanvas().toDataURL(editFile.type).split(',')[1])
        let arr = new Uint8Array(binStr.length)
        for (let i = 0; i < binStr.length; i++) {
            arr[i] = binStr.charCodeAt(i)
        }
        data.file = new File([arr], data.name, {type: editFile.type})
        data.size = data.file.size
    }
    upload.update(editFile.id, data)
    editFile.error = ''
    editFile.show = false
}

// add folder
function onAddFolder() {
    if (!upload.features.directory) {
        alert('Your browser does not support')
        return
    }
    let input = document.createElement('input')
    input.style = "background: rgba(255, 255, 255, 0);overflow: hidden;position: fixed;width: 1px;height: 1px;z-index: -1;opacity: 0;"
    input.type = 'file'
    input.setAttribute('allowdirs', true)
    input.setAttribute('directory', true)
    input.setAttribute('webkitdirectory', true)
    input.multiple = true
    document.querySelector("body").appendChild(input)
    input.click()
    input.onchange = (e) => {
        upload.addInputFile(input).then(function () {
            document.querySelector("body").removeChild(input)
        })
    }
}

function onAddData() {
    addData.show = false
    if (!upload.features.html5) {
        alert('Your browser does not support')
        return
    }
    let file = new window.File([addData.content], addData.name, {
        type: addData.type,
    })
    upload.add(file)
}


</script>
