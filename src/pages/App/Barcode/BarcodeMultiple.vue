<script>
import { ref, watch } from 'vue'
import { VTextarea, VField, VButton } from '@pathscale/vue3-ui'

export default {
  name: 'BarcodeMultiple',
  components: { VTextarea, VField, VButton },
  props: {
    disabled: [Boolean]
  },
  emits: ['printBarcodes'],
  setup(props, ctx) {
    const bindDisabled = ref(props.disabled !== false ? { disabled: 'disabled' } : {})
    const text = ref('')
    const textlines = ref([])
    const data = ref(null)
    const error = ref(null)

    /**
     * Print to screen multiple barcodes.
     * @param {object} event
     * @returns {void}
     */
    function splitToStringTextarea(event) {
      textlines.value = text.value.split('\n')
      ctx.emit('printBarcodes', textlines.value)
    }

    /**
     * Sent to printer all barcodes.
     * @returns {void}
     */
    async function toPrinter() {
      const arrCodes = []
      const barcodesArr = text.value.split('\n')

      barcodesArr.forEach(elm => {
        arrCodes.push({
          barcode: elm,
          format: 'CODE128'
        })
      })

      const datax = { data: [ arrCodes ] }

      await fetch(process.env.VUE_APP_API_SERVER, {
        method: 'POST',
        mode: 'no-cors',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(datax)
      })
        .then(res => res.json())
        .then(json => (data.value = json))
        .catch(err => (error.value = err))

      ctx.emit('printBarcodes', data)
    }

    /**
     * Clear textarea with all barcodes.
     * @param {object} event
     * @returns {void}
     */
    function clearMultipleBarcodes(event) {
      textlines.value = []
      text.value = ''
      ctx.emit('printBarcodes', textlines.value)
    }

    /**
     * V-bind to disable all components.
     * @returns {void}
     */
    watch(() => props.disabled, (currentValue, oldValue) => {
      bindDisabled.value = currentValue ? { disabled: 'disabled' } : {}
    })

    return {
      bindDisabled,
      splitToStringTextarea,
      clearMultipleBarcodes,
      toPrinter,
      text
    }
  }
}
</script>

<template>
    <v-field label="Barcodes" label-for="barcodes-input">
      <v-textarea
        id="barcodes-input"
        placeholder="Insert one barcode per line"
        v-bind="bindDisabled" v-model="text"/>
      <p class="help">Each barcode is separated by <kbd>Enter</kbd></p>
    </v-field>
    <div class="buttons is-justify-content-center">
      <v-button
type="is-success" rounded tag="a" href="#"
        @click="toPrinter" v-bind="bindDisabled">
        <span class="icon mx-1">
          <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66
            18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021
            15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015
            1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621
            0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
        Print Barcodes
      </v-button>
      <v-button
        type="is-primary" rounded tag="a" href="#"
        v-bind="bindDisabled"
        @click="splitToStringTextarea">
          <span class="icon mx-1">
            <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013
                15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
                stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          Print to Screen
      </v-button>
      <v-button
        type="is-dark" v-bind="bindDisabled"
        @click="clearMultipleBarcodes" rounded>
        <span class="icon mx-1">
          <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path
d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25
              2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5
              0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
              stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>
        Clear Barcodes
      </v-button>
    </div>
</template>

<style>
kbd {
    border: 2px solid #cdcdcd;
    border-radius: 0.25rem;
    box-shadow: #cdcdcd;
    box-shadow: inset 0 -1px 0 0 #cdcdcd;
    font-size: .825rem;
    padding: .25rem;
}
</style>
