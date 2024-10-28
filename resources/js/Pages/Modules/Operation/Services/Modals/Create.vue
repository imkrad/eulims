<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 900px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Test Service' : 'Edit Test Service'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
            
        <form class="customform">
            <BRow>
                <BCol lg="6" class="mt-1 mb-1">
                    <InputLabel for="classification_id" value="Laboratory Type" :message="form.errors.laboratory_id"/>
                    <Multiselect :options="dropdowns.laboratories"
                    @input="handleInput('laboratory_id')" 
                    disabled="true"
                    :searchable="true" v-model="form.laboratory_id" 
                    placeholder="Select Laboratory" ref="multiselect1"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-1">
                    <InputLabel for="classification_id" value="Laboratory Type" :message="form.errors.laboratory_type"/>
                    <Multiselect :options="dropdowns.types" 
                    @input="handleInput('laboratory_type')" 
                    :searchable="true" v-model="form.laboratory_type" 
                    placeholder="Select Laboratory type" ref="multiselect1"/>
                </BCol>
                <BCol lg="6" class="mt-1 mb-1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="sampletype" value="Sample type" :message="form.errors.sampletype_id"/>
                            <Multiselect @search-change="checkSearchSample" 
                            @input="handleInput('sampletype_id')"
                            :options="sampletypes" label="name" :searchable="true" 
                            :clearOnSearch="true"
                            v-model="form.sampletype_id" 
                            placeholder="Select Sample type" ref="multiselectS"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd(30,'Sample Type')" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1" :disabled="(form.laboratory_type && sampletypes.length === 0) ? false : true"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="6" class="mt-1 mb-1">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="testname" value="Test name" :message="form.errors.testname_id"/>
                            <Multiselect @search-change="checkSearchTest" 
                            @input="handleInput('testname_id')"
                            :options="testnames" label="name" :searchable="true" 
                            v-model="form.testname_id" 
                            placeholder="Select Test name" ref="multiselectT"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd(31,'Test Name')" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1" :disabled="(form.laboratory_type && testnames.length === 0) ? false : true"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
            </BRow>
        </form>
        <BRow>
            <BCol lg="12" class="mt-1">
                <hr class="text-muted"/>
            </BCol>
            <BCol lg="12" class="mt-0">
                    <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="filter.keyword" placeholder="Search Method Reference" class="form-control" style="width: 35%;">
                        <b-button @click="openMethod()" type="button" variant="primary" :disabled="(form.laboratory_type) ? false : true">
                            <i class="ri-add-circle-fill align-bottom me-1"></i> Add
                        </b-button>
                    </div>
                </b-col>
                <div class="table-responsive mt-2">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <td></td>
                                <td>Method</td>
                                <td class="text-center">Reference</td>
                                <td class="text-center">Fee</td>
                            </tr>
                        </thead>
                        <tbody v-if="methods.length > 0">
                            <tr v-for="(list,index) in methods" v-bind:key="index">
                                <td class="text-center"> 
                                    <input class="form-check-input me-1" v-model="form.method_id" name="method" type="radio" :value="list.id">
                                </td>
                                <td>{{(list.method.short) ? list.method.short : list.method.name}}</td>
                                <td class="text-center">{{list.reference.name}}</td>
                                <td class="text-center">{{list.fee}}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="4" class="text-center text-muted fs-12">No methods found. Please use search box.</td>
                            </tr>
                        </tbody>
                    </table>
               </div>
            </BCol>
        </BRow>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Add @selected="set" ref="add"/>
    <Method @selected="setMethod" ref="method"/>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Add from './Add.vue';
import Method from './Method.vue';
import Multiselect from '@/Shared/Components/Forms/Multiselect.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect, Add, Method },
    props: ['dropdowns','laboratory'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                laboratory_id: this.laboratory,
                laboratory_type: null,
                sampletype_id: null,
                testname_id: null,
                method_id: null,
                option: 'create'
            }),
            filter: {
                keyword: null,
                sampletype: null,
                testname: null
            },
            sampletypes: [],
            testnames: [],
            methods: [],
            type: null,
            showModal: false,
            editable: false,
        }
    },
    watch: {
        'form.laboratory_type'(){
            this.sampletypes = [];
            this.testnames = [];
            this.$refs.multiselectT.clear();
            this.$refs.multiselectS.clear();
            this.form.sampletype_id = null;
            this.form.testname_id = null;
        },
        'filter.keyword'(newVal){
            this.checkSearchMethod(newVal)
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        }, 
        submit(){
            this.form.post('/services',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('message',true);
                    this.hide();
                },
            });
        },
        checkSearchSample: _.debounce(function(string) {
            (string) ? this.fetchSample(string) : '';
            this.filter.sampletype = string;
        }, 300),
        fetchSample(code){
            this.sampletypes = [];
            axios.get('/services',{
                params: {
                    option: 'search',
                    laboratory_type: this.form.laboratory_type,
                    type: 30,
                    keyword: code
                }
            })
            .then(response => {
                this.sampletypes = response.data;
            })
            .catch(err => console.log(err));
        },
        checkSearchTest: _.debounce(function(string) {
            this.fetchTest(string);
            this.filter.testname = string;
        }, 300),
        fetchTest(code){
            axios.get('/services',{
                params: {
                    option: 'search',
                    laboratory_type: this.form.laboratory_type,
                    type: 31,
                    keyword: code
                }
            })
            .then(response => {
                this.testnames = response.data;
            })
            .catch(err => console.log(err));
        },
        checkSearchMethod: _.debounce(function(string) {
            this.fetchMethod(string);
        }, 300),
        fetchMethod(code){
            axios.get('/services',{
                params: {
                    option: 'methods',
                    count: 5,
                    laboratory_type: this.form.laboratory_type,
                    keyword: code
                }
            })
            .then(response => {
                this.methods = response.data.data;
            })
            .catch(err => console.log(err));
        },
        set(data){
            if(this.type === 30){
                this.fetchSample(data.name);
                this.$refs.multiselectS.emitSelectedValues(data.value);
            }else if(this.type === 31){
                this.fetchTest(data.name);
                this.$refs.multiselectT.emitSelectedValues(data.value);
            }
        },
        setMethod(data){
            this.fetchMethod(data.method.name);
        },
        openAdd(type,name){
            this.type = type;
            const key = (type == 30) ? this.filter.sampletype : this.filter.testname;
            this.$refs.add.show(type,this.form.laboratory_type,name,key);
        },
        openMethod(){
            this.$refs.method.show(this.form.laboratory_type);
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.methods = [];
            this.$refs.multiselect1.clear();
            this.$refs.multiselectS.clear();
            this.$refs.multiselectT.clear();
            this.form.method_id = null;
            this.filter.keyword = null;
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>