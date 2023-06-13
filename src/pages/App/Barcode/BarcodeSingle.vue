<script>
import { ref, watch } from 'vue'
import { VInput, VField, VButton } from '@pathscale/vue3-ui'

export default {
  name: 'BarcodeSingle',
  components: { VInput, VField, VButton },
  props: {
    value: [String, Number],
    disabled: [Boolean]
  },
  emits: ['printBarcode'],
  setup(props, ctx) {
    const bindDisabled = ref(props.disabled !== false ? { disabled: 'disabled' } : {})
    const data = ref(null)
    const error = ref(null)

    /**
     * Print on screen a single barcode.
     * @returns {void}
     */
    function toScreenCode() {
      ctx.emit('printBarcode', props.value)
    }

    /**
     * Send a single barcode to Printer.
     * @returns {void}
     */
    async function toPrinterCode() {
      const datax = {
        data: [
          {
            barcode: props.value || '00000000',
            format: 'CODE128'
          }
        ]
      }

      await fetch(process.env.VUE_APP_API_SERVER, {
        method: 'POST',
        mode: 'no-cors',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(datax)
      })
        .then(res => res.json())
        .then(json => (data.value = json))
        .catch(err => (error.value = err))

      ctx.emit('printBarcode', data)
    }

    /**
     * V-bind to disable all component.
     * @returns {void}
     */
    watch(() => props.disabled, (currentValue, oldValue) => {
      bindDisabled.value = currentValue ? { disabled: 'disabled' } : {}
    })

    return {
      toScreenCode,
      toPrinterCode,
      bindDisabled
    }
  }
}
</script>

<template>
  <v-field label="Barcode" label-for="barcode-input">
    <v-input id="barcode-input" v-model="value" placeholder="Insert one barcode" v-bind="bindDisabled" />
  </v-field>
  <div class="buttons is-justify-content-center">
    <v-button type="is-success" rounded tag="a" href="#" @click="toPrinterCode" v-bind="bindDisabled">
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
      Print Barcode {{ value }}
    </v-button>
    <v-button type="is-primary" rounded tag="a" href="#" @click="toScreenCode" v-bind="bindDisabled">
      <span class="icon mx-1">
        <svg aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path
d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013
            15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
            stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
      Print to Screen {{ value }}
    </v-button>
  </div>
</template>
