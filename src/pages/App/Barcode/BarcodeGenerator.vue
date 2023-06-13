<script>
// importing hyperscript from vue to create the HTML element
import { h, onMounted } from 'vue'

// importing jsbarcode method from jsbarcode library
// import JsBarcode from "jsbarcode";
export default {
  name: 'BarcodeGenerator',
  // defining props and props type to be used in BarcodeGenerator component
  props: {
    value: [String, Number],
    format: [String],
    width: [String, Number],
    height: [String, Number],
    displayValue: {
      type: [String, Boolean],
      default: true,
    },
    fontOptions: [String],
    font: [String],
    textAlign: [String],
    textPosition: [String],
    textMargin: [String, Number],
    fontSize: [String, Number],
    background: [String],
    lineColor: [String],
    margin: [String, Number],
    marginTop: [String, Number],
    marginBottom: [String, Number],
    marginLeft: [String, Number],
    marginRight: [String, Number],
    flat: [Boolean],
    elementTag: {
      type: String,
      default: 'svg',
      validator(value) {
        return ['canvas', 'svg', 'img'].includes(value)
      },
    },
  },
  setup(props) {
    const settings = {
      format: (props.format === undefined)
        ? 'auto'
        : JSON.parse(JSON.stringify(props.format)),
      width: (props.width === undefined)
        ? 2
        : JSON.parse(JSON.stringify(props.width)),
      height: (props.height === undefined)
        ? 100
        : JSON.parse(JSON.stringify(props.height)),
      displayValue: (props.displayValue === undefined)
        ? true
        : JSON.parse(JSON.stringify(props.displayValue)),
      fontOptions: (props.fontOptions === undefined)
        ? ''
        : JSON.parse(JSON.stringify(props.fontOptions)),
      font: (props.font === undefined)
        ? 'monospace'
        : JSON.parse(JSON.stringify(props.font)),
      textAlign: (props.textAlign === undefined)
        ? 'center'
        : JSON.parse(JSON.stringify(props.textAlign)),
      textPosition: (props.textPosition === undefined)
        ? 'bottom'
        : JSON.parse(JSON.stringify(props.textPosition)),
      textMargin: (props.textMargin === undefined)
        ? 2
        : JSON.parse(JSON.stringify(props.textMargin)),
      fontSize: (props.fontSize === undefined)
        ? 20
        : JSON.parse(JSON.stringify(props.fontSize)),
      background: (props.background === undefined)
        ? '#fff'
        : JSON.parse(JSON.stringify(props.background)),
      lineColor: (props.lineColor === undefined)
        ? '#000'
        : JSON.parse(JSON.stringify(props.lineColor)),
      margin: (props.margin === undefined)
        ? 10
        : JSON.parse(JSON.stringify(props.margin)),
      marginTop: (props.marginTop === undefined)
        ? undefined
        : JSON.parse(JSON.stringify(props.marginTop)),
      marginBottom: (props.marginBottom === undefined)
        ? undefined
        : JSON.parse(JSON.stringify(props.marginBottom)),
      marginLeft: (props.marginLeft === undefined)
        ? undefined
        : JSON.parse(JSON.stringify(props.marginLeft)),
      marginRight: (props.marginRight === undefined)
        ? undefined
        : JSON.parse(JSON.stringify(props.marginRight)),
      flat: (props.margin === undefined)
        ? false
        : JSON.parse(JSON.stringify(props.flat))
    }

    onMounted(() => {
      JsBarcode(
        `#barcodegen${props.value}`,
        (props.value === undefined || props.value.trim() === '')
          ? '00000000'
          : JSON.parse(JSON.stringify(props.value)),
        settings
      )
    })
    return {}
  },

  // render function to create the HTML element
  render() {
    return h(this.elementTag, { id: [`barcodegen${this.value}`] })
  }

}
</script>
