<script>

import { ref, onMounted } from 'vue'
import { VColumns, VColumn, VInput, VButton } from '@pathscale/vue3-ui'
import BarcodeGenerator from './BarcodeGenerator'
import BarcodeSingle from './BarcodeSingle'
import BarcodeMultiple from './BarcodeMultiple'
import Notif from './Notif'

export default {
  name: 'Barcode',
  components: { BarcodeGenerator, BarcodeSingle, BarcodeMultiple,
    VColumns, VColumn, VInput, VButton, Notif
  },
  setup() {
    const singleOrMultiple = ref(false)
    const isDisableSingle = ref(false)
    const isDisableMultiple = ref(true)
    const sbvalue = ref('00000000')
    const mbvalue = ref([])
    const componentKey = ref(0)
    const showNotif = ref(false)
    const notifMsg = ref('')
    const classTypeNotif = ref('is-success')
    const header = ref('');
    const subHeader = ref('');

    // https://michaelnthiessen.com/key-changing-technique/
    // https://michaelnthiessen.com/force-re-render/
    // This force to Render the image component for barcodes
    const forceRerender = () => {
      componentKey.value += 1
    }

    /**
     * Generate a single barcode on screen or send to printer.
     * @param {string | object} single a barcode or a Proxy with msg
     * @returns {void}
     */
    function generate(single) {
      if (typeof single === 'string') {
        sbvalue.value = single
      } else {
        single.value.results.forEach(elm => {
          if (elm.type === 'success') {
            showNotif.value = true
            notifMsg.value = elm.msg
            classTypeNotif.value = 'is-success'
          } else {
            showNotif.value = true
            notifMsg.value = 'Error on printing'
            classTypeNotif.value = 'is-danger'
          }
        })
      }
      forceRerender()
    }

    /**
     * Generate multiple barcodes on screen or send to printer.
     * @param {string | object} multiple muliple barcodes or a Proxy with msg
     * @returns {void}
     */
    function generateMultiple(multiple) {
      if (Array.isArray(multiple)) {
        mbvalue.value = multiple
      } else {
        multiple.value.results.forEach(elm => {
          if (elm.type === 'success') {
            showNotif.value = true
            notifMsg.value = elm.msg
            classTypeNotif.value = 'is-success'
          } else {
            showNotif.value = true
            notifMsg.value = 'Error on printing'
            classTypeNotif.value = 'is-danger'
          }
        })
      }

    }

    /**
     * Enable or disable switch for single or multiple barcode.
     * @param {object} event
     * @returns {void}
     */
    function onEnableMultiple(event) {
      isDisableSingle.value = !isDisableSingle.value
      isDisableMultiple.value = !isDisableMultiple.value
    }

    function closeMsg() {
      showNotif.value = false
    }

    function saveHeaders(header, subheader) {
      localStorage.header = header
      localStorage.subheader = subheader
    }

    onMounted(() => {
      const testHeaderSet = (localStorage.getItem('header') !== null)
      const testSubHeaderSet = (localStorage.getItem('subheader') !== null)

      if (testHeaderSet) {
        header.value = localStorage.getItem('header')
      }

      if (testSubHeaderSet) {
        subHeader.value = localStorage.getItem('subheader')
      }
    })

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
      closeMsg,
      header,
      subHeader,
      saveHeaders
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
    <notif v-show="showNotif" :class="classTypeNotif" @click-delete-msg="closeMsg">
      {{ (showNotif) ? notifMsg : '' }}
    </notif>
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
            <v-field label="Header" label-for="header-input" message="Only 30 characters allowed.">
              <v-input id="header-input" v-model="header" placeholder="Enter a header to print on labels"
                maxlength="30" />
            </v-field>
            <v-field label="Sub-header" label-for="sub-header-input" message="Only 30 characters allowed.">
              <v-input id="sub-header-input" v-model="subHeader" placeholder="Enter a sub-header to print labels"
                maxlength="30" />
            </v-field>
            <v-button type="is-link" @click="saveHeaders(header, subHeader)">
              <span class="icon mx-1">
                <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5"
                  viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    stroke-linecap="round" stroke-linejoin="round">
                  </path>
                </svg>
              </span>
              Save headers
            </v-button>
          </p>
        </div>
        <div class="tile is-child box">
          <p class="title">One Barcode</p>
          <p>
            <barcode-single
              @print-barcode="generate($event)"
              :disabled="isDisableSingle" :header="header" :subHeader="subHeader"/>
          </p>
        </div>
        <div class="tile is-child box">
          <p class="title">Multiple Barcodes</p>
          <p>
            <barcode-multiple
              :disabled="isDisableMultiple"
              @print-barcodes="generateMultiple($event)"
              :header="header" :subHeader="subHeader"/>
          </p>
        </div>
      </div>
      <div class="tile is-parent">
        <div class="tile is-child box">
          <p class="title">Barcodes box</p>
          <v-columns v-if="!isDisableSingle">
            <v-column size="is-12">
              <p class="px-4 py-4">
                <span v-if="header !== ''"> {{ header }} </span><br v-if="header !== ''">
                <span v-if="subHeader !== ''" style="display:inherit;"> {{ subHeader }} </span>
                <barcode-generator
                  :value="sbvalue"
                  format="CODE128"
                  line-color="#000"
                  :width="3"
                  :height="90"
                  element-tag="img"
                  :key="componentKey"/>
              </p>
            </v-column>
          </v-columns>
          <v-columns v-else-if="!isDisableMultiple" multiline>
            <v-column v-for="(value, key) in mbvalue" :key="key" size="is-4">
              <p class="px-4 py-4">
                <span v-if="header !== ''"> {{ header }} </span><br v-if="header !== ''">
                <span v-if="subHeader !== ''"> {{ subHeader }} </span>
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
