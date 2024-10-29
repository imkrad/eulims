<template>
    <b-col lg="12">
        <b-card no-body>
            <div class="bg-info-subtle">
                <b-card-body class="pb-0 px-4">
                    <b-row class="mb-3">
                        <b-col md>
                            <b-row class="align-items-center g-3">
                                <b-col md>
                                    <div>
                                        <h4 class="fw-semibold text-primary">{{(selected.code) ? selected.code+' - ' : ''}} <span class="fs-15">{{selected.customer.name}}</span></h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <!-- <div>
                                                <i class="ri-user-fill align-bottom me-1"></i>
                                                {{selected.customer.name}}
                                            </div>
                                             <div class="vr"></div> -->
                                            <div>
                                                <i class="ri-map-pin-2-fill align-bottom me-1"></i>
                                                {{selected.customer.address.name}}
                                            </div>
                                            <div class="vr"></div>
                                            <div><i class="ri-account-circle-fill align-bottom me-1"></i>{{selected.conforme}}</div>
                                            <div class="vr"></div>
                                            <div><i class="ri-phone-fill align-bottom me-1"></i>{{selected.conforme_no}}</div>
                                            <div class="vr" v-if="selected.status.name === 'Pending' || selected.status.name === 'For Payment'"></div>
                                            <div v-if="selected.status.name === 'Pending' || selected.status.name === 'For Payment'"><i class="ri-wallet-3-fill align-bottom me-1"></i>{{ (selected.customer.wallet != null) ? selected.customer.wallet.available : 'No Balance'}}</div>
                                        </div>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col md="auto">
                            <div class="hstack gap-1 flex-wrap mt-2">
                                <b-button v-if="selected.status.name === 'Pending'" :disabled="(analyses == 0) ? true : false" @click="openSave(selected.id)" variant="primary" v-b-tooltip.hover title="Save">
                                    <i class="ri-save-fill align-bottom"></i> Save
                                </b-button>
                                <b-button v-if="selected.status.name === 'For Payment' && selected.customer.wallet != null && selected.customer.wallet.available != '₱0.00'" @click="openWallet(selected.id,selected.customer,selected.payment)" variant="primary" v-b-tooltip.hover title="Payment">
                                    <i class="ri-wallet-fill align-bottom"></i> Use Wallet
                                </b-button>
                                <b-button v-if="selected.status.name !== 'Pending'" @click="openPrint(selected.qr)" variant="primary" v-b-tooltip.hover title="Print">
                                    <i class="ri-printer-fill"></i>
                                </b-button>
                                <Link href="/tsrs">
                                    <b-button variant="soft-danger">
                                        <i class="ri-close-circle-fill align-bottom"></i> 
                                    </b-button>
                                </Link>
                            </div>
                        </b-col>
                    </b-row>
                    
                </b-card-body>
            </div>
        </b-card>
    </b-col>
    <Save ref="save"/>
    <Wallet ref="wallet"/>
</template>
<script>
import Save from '../Modals/Save.vue';
import Wallet from '../Modals/Wallet.vue';
export default {
    components: { Save, Wallet }, 
    props:['selected','analyses'],
    methods: {
        openSave(id){
            this.$refs.save.show(id,this.selected.customer.industry);
        },
        openWallet(id,customer,payment){
            this.$refs.wallet.show(id,customer,payment);
        },
        openPrint(id){
            window.open('/tsrs?option=print&id='+id);
        },
    }
}
</script>