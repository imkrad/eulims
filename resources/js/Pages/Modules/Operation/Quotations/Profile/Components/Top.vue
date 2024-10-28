<template>
    <b-col lg="12">
        <b-card no-body>
            <div class="bg-warning-subtle">
                <b-card-body class="pb-0 px-4">
                    <b-row class="mb-3">
                        <b-col md>
                            <b-row class="align-items-center g-3">
                                <b-col md>
                                    <div>
                                        <h4 class="fw-semibold text-primary">{{selected.customer.name}}</h4>
                                        <div class="hstack gap-3 flex-wrap">
                                            <div>
                                                <i class="ri-map-pin-2-fill align-bottom me-1"></i>
                                                {{selected.customer.address.name}}
                                            </div>
                                            <div class="vr"></div>
                                            <div><i class="ri-account-circle-fill align-bottom me-1"></i>{{selected.conforme}}</div>
                                                <div class="vr"></div>
                                            <div><i class="ri-phone-fill align-bottom me-1"></i>{{selected.conforme_no}}</div>
                                        </div>
                                    </div>
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col md="auto">
                            <div class="hstack gap-1 flex-wrap mt-2">
                                <b-button v-if="selected.status.name === 'Ongoing' || selected.status.name === 'Completed'" @click="openSubmit(selected)" variant="primary" v-b-tooltip.hover title="Save">
                                    <i class="ri-save-fill align-bottom"></i> Convert to TSR
                                </b-button>
                                <b-button v-if="selected.status.name === 'Pending'" :disabled="(analyses.length == 0) ? true : false" @click="openSave(selected.id)" variant="primary" v-b-tooltip.hover title="Save">
                                    <i class="ri-save-fill align-bottom"></i> Save
                                </b-button>
                                <b-button v-if="selected.status.name !== 'Pending'" @click="openPrint(selected.qr)" variant="primary" v-b-tooltip.hover title="Print">
                                    <i class="ri-printer-fill"></i>
                                </b-button>
                                <Link href="/quotations">
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
    <Submit ref="submit"/>
</template>
<script>
import Save from '../Modals/Save.vue';
import Submit from '../Modals/Submit.vue';
export default {
    components: { Save, Submit }, 
    props:['selected','analyses'],
    methods: {
        openSave(id){
            this.$refs.save.show(id);
        },
        openPrint(id){
            window.open('/quotations?option=print&id='+id);
        },
        openSubmit(data){
            this.$refs.submit.show(data,this.analyses);
        },
    }
}
</script>