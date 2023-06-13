import { IService } from '@pathscale/wss-adapter/types'

const service: IService = {
  remote: 'wss://api.salesaction.pk:8447',
  methods: {
    10020: {
      name: 'Login',
      parameters: ['username', 'password', 'serviceCode', 'deviceId', 'deviceOS'],
    },
  },
}

export default service
