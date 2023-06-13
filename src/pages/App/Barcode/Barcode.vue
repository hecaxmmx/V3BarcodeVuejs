<script>
import { ref } from 'vue'
import { VColumns, VColumn } from '@pathscale/vue3-ui'
import BarcodeGenerator from './BarcodeGenerator'
import BarcodeSingle from './BarcodeSingle'
import BarcodeMultiple from './BarcodeMultiple'
import Notif from './Notif'

export default {
  name: 'Barcode',
  components: { BarcodeGenerator, BarcodeSingle, BarcodeMultiple,
    VColumns, VColumn, Notif
  },
  setup() {
    const singleOrMultiple = ref(false)
    const isDisableSingle = ref(false)
    const isDisableMultiple = ref(true)
    const sbvalue = ref('00000000')
    const mbvalue = ref([])
    const componentKey = ref(0)
    const showNotif = ref(false)
    const notifMsg = ref("")
    const classTypeNotif = ref("is-success")

    // https://michaelnthiessen.com/key-changing-technique/
    // https://michaelnthiessen.com/force-re-render/
    // This force to Render the image component for barcodes
    const forceRerender = () => {
      componentKey.value += 1
    }

    /**
     * Generate a single barcode on screen or send to printer
     * @param {string|Proxy} single a barcode or a Proxy with msg
     */
    function generate(single) {
      if (typeof single === "string") {
        sbvalue.value = single
      } else {
        single.value.results.forEach(elm => {
          if (elm.type === 'success') {
            showNotif.value = true
            notifMsg.value = elm.msg
            classTypeNotif.value = "is-success"
          } else {
            showNotif.value = true
            notifMsg.value = 'Error on printing'
            classTypeNotif.value = "is-danger"
          }
        })
      }
      forceRerender()
    }

    /**
     * Generate multiple barcodes on screen or send to printer
     * @param {string|Proxy} multiple muliple barcodes or a Proxy with msg
     */
    function generateMultiple(multiple) {
      if (Array.isArray(multiple)) {
        mbvalue.value = multiple
      } else {
        multiple.value.results.forEach(elm => {
          if (elm.type === 'success') {
            showNotif.value = true
            notifMsg.value = elm.msg
            classTypeNotif.value = "is-success"
          } else {
            showNotif.value = true
            notifMsg.value = 'Error on printing'
            classTypeNotif.value = "is-danger"
          }
        })
      }
      
    }

    /**
     * Enable or disable switch for single or multiple barcode
     * @param {Object} event 
     */
    function onEnableMultiple(event) {
      isDisableSingle.value = !isDisableSingle.value
      isDisableMultiple.value = !isDisableMultiple.value
    }

    function closeMsg() {
      showNotif.value = false
    }

    return {
      singleOrMultiple,
      isDisableSingle,
      isDisableMultiple,
      onEnableMultiple,
      sbvalue,
      mbvalue,
      generate,
      generateMultiple,
      componentKey,
      showNotif,
      notifMsg,
      classTypeNotif,
      closeMsg
    }
  }
}
</script>

<template>
  <section class="section has-text-centered">
    <div class="container">
    <v-image centered src="https://vue3.dev/vue3-ui-logo.png" size="is-128x128" />
    <h1 class="title">
      Barcode Generator
    </h1>
    <Notif v-show="showNotif" :class="classTypeNotif" @click-delete-msg="closeMsg">
      {{ (showNotif) ? notifMsg : '' }}
    </Notif>
    <div class="tile is-ancestor">
      <div class="tile is-4 is-vertical is-parent">
        <div class="tile is-child box">
          <p class="title">One or multiple barcode</p>
          <p>
            <v-field>
              <v-switch v-model="singleOrMultiple" value="Multiple" @click="onEnableMultiple">
                Multiple
              </v-switch>
            </v-field>
          </p>
        </div>
        <div class="tile is-child box">
          <p class="title">One Barcode</p>
          <p>
            <barcode-single
              @print-barcode="generate($event)"
              :disabled="isDisableSingle"
            />
          </p>
        </div>
        <div class="tile is-child box">
          <p class="title">Multiple Barcodes</p>
          <p>
            <barcode-multiple
              :disabled="isDisableMultiple"
              @print-barcodes="generateMultiple($event)"
            />
          </p>
        </div>
      </div>
      <div class="tile is-parent">
        <div class="tile is-child box">
          <p class="title">Barcodes box</p>
          <p class="px-4 py-4" v-if="!isDisableSingle">
            <barcode-generator
              :value="sbvalue"
              format="CODE128"
              line-color="#000"
              :width="3"
              :height="90"
              element-tag="img"
              :key="componentKey"/>
          </p>
          <v-columns v-else-if="!isDisableMultiple.value" multiline>
            <v-column v-for="(value, key) in mbvalue" :key="key" size="is-4">
              <p class="px-4 py-4">
                {{ `barcode: ${key}` }}
                <barcode-generator
                  :value="value"
                  format="CODE128"
                  line-color="#000"
                  :width="3"
                  :height="90"
                  element-tag="img"
                  :key="key"/>
              </p>
            </v-column>
          </v-columns>
        </div>
      </div>
    </div>
    <br />
    </div>
  </section>
</template>
