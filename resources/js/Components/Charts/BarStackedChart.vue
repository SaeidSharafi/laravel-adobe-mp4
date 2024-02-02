<template>
  <div :style="`height: ${height}px;`">
    <VChart title="BarChart" :option="chartOptions" />
  </div>
</template>

<script setup>
import { use } from "echarts/core"
import { CanvasRenderer } from "echarts/renderers"
import { BarChart } from "echarts/charts"
import { GridComponent, LegendComponent, TitleComponent, TooltipComponent } from "echarts/components"
import VChart, { THEME_KEY } from "vue-echarts";
import { computed, provide } from "vue";
import { useStyleStore } from "@/admin/style";
import { useI18n } from "vue-i18n";
import { replace } from "lodash-es";

use([
  CanvasRenderer,
  BarChart,
  GridComponent,
  TitleComponent,
  TooltipComponent,
  LegendComponent,
])
const { t, d, n } = useI18n({});

const styleStore = useStyleStore();
provide(THEME_KEY, styleStore.darkMode ? 'dark' : 'light');

const family =
  '"IRANSansFaNum", Nunito, ui-sans-serif, system-ui,' +
  ' -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,' +
  ' "Helvetica Neue", Arial, "Noto Sans", sans-serif, ' +
  '"Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'


const props = defineProps({
  data: {
    default: []
  },
  title: {
    type: String,
    default: ''
  },
  width: {
    type: Number,
    default: 400
  },
  height: {
    type: Number,
    default: 400
  },
  cssClasses: {
    default: '',
    type: String
  },
  formatValue: {
    type: Function,
    default: (x) => x.value
  },
  plugins: {
    type: Object,
    default: () => {
    }
  },
  stepSize: {
    type: Number,
    default: 10
  },
  showTotal: {
    type: Boolean,
    default: true
  },
  normalized: {
    type: Boolean,
    default: false
  },
  label: {
    type: Boolean,
    default: true
  },
  labelAfter: {
    type: String,
    default: '',
  },
  xAxisTitle: {
    type: String,
    default: 'Month'
  },
  yAxisTitle: {
    type: String,
    default: 'Value'
  },
  colors: {
    type: Array,
    default: undefined,
  }
})

const randomInt = (min, max) => {
  return Math.floor(Math.random() * (max - min + 1)) + min;
};

function generateColor(light = 50) {
  let h = randomInt(0, 270);
  let s = randomInt(80, 98);
  let l = randomInt(40, 50);
  return `hsl(${h},${s}%,${l}%)`;
}

const computedTextColor = computed(() => {
  return styleStore.darkMode ? '#EFE7EB' : "#333333";
})
const computedBgColor = computed(() => {
  return styleStore.darkMode ? '#1E293BFF' : "#D8E3F0";
})
const chartOptions = computed(() => {
  return {
    textStyle: {
      fontFamily: family,
    },
    title: {
      text: props.title,
    },
    tooltip: {
      trigger: 'axis',
      axisPointer: {
        type: 'shadow'
      },
      formatter: function (params) {
        let content = '';
        params.forEach(element => {

          let value = props.formatValue(element)
          content += `<div class='flex justify-between'>
            <div>
              ${element.marker}
              ${element.seriesName}</div>
              <div class="mr-2">${value}</div>
              </div>`
        });
        return content
      }
    },
    legend: {
      // Try 'horizontal'
      orient: 'horizontal',
      type: 'scroll'
    },
    grid: {
      left: '3%',
      right: '4%',
      bottom: '3%',
      containLabel: true
    },
    xAxis: {
      type: 'category',
      data: props.data.categories,
      axisTick: {
        alignWithLabel: true
      },
      axisLabel: {
        formatter: (val) => {
          // return val + " \n asdsad"
          return val.replace('|','\n') + ""
        }
      },
    },
    yAxis: {
      type: "value",
      max: props.normalized ? 100 : null
    },
    label: {
      show: props.label,
      formatter: (params) => {

        let value = params.value > 0
          ? params.seriesName + ' ' + params.value + props.labelAfter
          : ''
        return `{a|${value}}`
      },
      rich: {
        a: {
          fontSize: 10
        },
      }
    },
    series: props.data.series,
  }
}
)
</script>

<style scoped></style>
