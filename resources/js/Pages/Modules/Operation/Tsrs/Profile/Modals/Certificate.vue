<template>
    <b-modal v-if="sample" v-model="showModal"  style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="Generate Certificate" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        
        <BRow>
            <BCol lg="12">
                <div class="input-group">
                    <button v-if="!sample.report" @click="generate()"  class="btn btn-primary" type="button">Generate Report Number</button>
                    <span v-else class="input-group-text fw-semibold fs-12">Report Number</span>
                    <input v-if="sample.report" type="text" class="form-control" :value="sample.report.code" style="text-align: right;" readonly>
                    <input v-else type="text" class="form-control" placeholder="Click to generate report number" style="text-align: right;" readonly>
                </div>
            </BCol>
            <BCol lg="12"><hr class="text-muted"/></BCol>
        </BRow> 

        <form class="customform">
            
            <BRow class="g-3 mt-1" v-for="(list,index) in sample.analyses" v-bind:key="index">
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Parameter"/>
                    <Textarea id="name" v-model="parameters[index].name" class="form-control" rows="1" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-0">
                    <InputLabel for="name" value="Result"/>
                    <Textarea id="name" v-model="parameters[index].result" class="form-control" rows="1" :light="true"/>
                </BCol>
            </BRow>
        </form>

        <div class="alert alert-danger alert-dismissible alert-label-icon rounded-label fade show mt-4" role="alert">
            <i class="ri-error-warning-line label-icon"></i><strong>QR Code</strong> - <span style="cursor: pointer;" @click="printQr()">Click here to print</span>
        </div>

        <template v-slot:footer>
            <!-- <b-button @click="hide()" variant="light" block>Cancel</b-button> -->
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Preview</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { InputLabel, TextInput, Textarea },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                sample_id: null,
                tsr_id: null,
                laboratory_type: null,
                option: 'report',
            }),
            parameters: [
                {name: null, result: null}
            ],
            sample: null,
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(data,id,type){
            this.sample = data;
            this.form.tsr_id = id;
            this.form.sample_id = data.id;
            this.form.laboratory_type = type;
            this.showModal = true;
            
            this.parameters = this.sample.analyses.map(analysis => {
                return { name: analysis.testname, result: null };
            });
        },
        submit(){
           window.open(this.currentUrl + '/samples?option=print&id='+this.sample.id+'&data='+JSON.stringify(this.parameters));
        },
        generate(){
            this.form.post('/samples',{
                preserveScroll: true,
                onSuccess: (response) => {
                   this.sample.report = response.props.flash.data;
                },
            });
        },
        printQr(id){
            window.open('/requests?option=testqr&id='+this.sample.id);
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>