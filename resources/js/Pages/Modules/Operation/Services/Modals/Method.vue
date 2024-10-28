<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Add Method Reference" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="method" value="Method" :message="errors.method_id"/>
                            <Multiselect @search-change="searchMethod" 
                            :options="methods" label="name" :searchable="true" 
                            v-model="form.method_id"
                            placeholder="Select Method" ref="multiselectM"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd(28,'Method')" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                            <!-- :disabled="(methods.length === 0) ? false : true" -->
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="reference" value="Reference" :message="errors.reference_id"/>
                            <Multiselect @search-change="searchReference" 
                            :options="references" label="name" :searchable="true" 
                            v-model="form.reference_id"
                            placeholder="Select Reference" ref="multiselectR"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd(29,'Reference')" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="fee" value="Fee" :message="errors.fee"/>
                    <!-- <div class="input-group mb-3">
                        <span class="input-group-text" style="height: 38px;">₱</span>
                        <TextInput id="fee" v-model="form.fee" type="text" class="form-control" autofocus placeholder="Please enter fee" autocomplete="fee" required :class="{ 'is-invalid': form.errors.fee }" :light="true"/>
                    </div> -->
                    <Amount @amount="amount" ref="testing" :readonly="false"/>
                    <!-- <InputError :message="form.errors.fee" /> -->
                </BCol>   
            </BRow>     
        </form>       
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Add @selected="set" ref="add"/>
</template>
<script>
import _ from 'lodash';
import Add from './Add.vue';
import { useForm } from '@inertiajs/vue3';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import Multiselect from '@/Shared/Components/Forms/Multiselect.vue';
import InputError from '@/Shared/Components/Forms/InputError.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputError, InputLabel, TextInput, Multiselect, Add, Amount },
    data(){
        return {
            currentUrl: window.location.origin,
            form: {
                method_id: null,
                reference_id:null,
                laboratory_type: null,
                fee: null,
                option: 'method'
            },
            filter: {
                method: null,
                reference: null
            },
            errors: [],
            methods: [],
            references: [],
            showModal: false
        }
    },
    methods: { 
        show(laboratory){
            this.form.laboratory_type = laboratory;
            this.showModal = true;
        },
        submit(){
            axios.post('/services', this.form)
            .then((response) => {
                this.$emit('selected',response.data);
                this.hide();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            });
        },
        amount(val){
            this.form.fee = val;
        },
        openAdd(type,name){
            this.type = type;
            const key = (type == 28) ? this.filter.method : this.filter.reference;
            this.$refs.add.show(type,this.form.laboratory_type,name,key);
        },
        searchMethod: _.debounce(function(string) {
            (string) ? this.fetchMethod(string) : '';
            this.filter.method = string;
        }, 300),
        fetchMethod(code){
            this.sampletypes = [];
            axios.get('/services',{
                params: {
                    option: 'search',
                    laboratory_type: this.form.laboratory_type,
                    type: 28,
                    keyword: code
                }
            })
            .then(response => {
                this.methods = response.data;
            })
            .catch(err => console.log(err));
        },
        searchReference: _.debounce(function(string) {
            (string) ? this.fetchReference(string) : '';
            this.filter.reference = string;
        }, 300),
        fetchReference(code){
            this.sampletypes = [];
            axios.get('/services',{
                params: {
                    option: 'search',
                    laboratory_type: this.form.laboratory_type,
                    type: 29,
                    keyword: code
                }
            })
            .then(response => {
                this.references = response.data;
            })
            .catch(err => console.log(err));
        },
        set(data){
            if(this.type == 28){
                this.fetchMethod(data.name);
                this.$refs.multiselectM.emitSelectedValues(data.value);
            }else{
                 this.fetchReference(data.name);
                this.$refs.multiselectR.emitSelectedValues(data.value);
            }
        },
        hide(){
            this.$refs.multiselectM.clear();
            this.$refs.multiselectR.clear();
            this.form.fee = null;
            this.showModal = false;
        }
    }
}
</script>