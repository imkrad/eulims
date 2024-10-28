<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 1000px;" header-class="p-3 bg-light" title="Add Testservice" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="4" class="mt-1">
                    <InputLabel for="sampletype" value="Laboratory"/>
                    <Multiselect 
                    :options="laboratories" label="name" :searchable="true" 
                    :clearOnSearch="true" object v-model="form.laboratory_type"
                    placeholder="Select Sample type" ref="multiselectS"/>
                </BCol>
                <BCol lg="4" class="mt-1">
                    <InputLabel for="sampletype" value="Sample type"/>
                    <Multiselect @search-change="checkSearchSample" 
                    :options="sampletypes" label="name" :searchable="true" 
                    :clearOnSearch="true" v-model="sampletype"
                    placeholder="Select Sample type" ref="multiselectS"/>
                </BCol>
                 <BCol lg="4" class="mt-1">
                    <InputLabel for="name" value="Total" :message="form.errors.fee"/>
                    <Amount @amount="amount" ref="testing" :readonly="true"/>
                </BCol>
            </BRow>
        </form>
        <BRow>
            <BCol lg="12" class="mt-1">
                <hr class="text-muted"/>
            </BCol>
            <BCol lg="12" class="mt-0" v-if="sampletype">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="searchTerm" @input="search" placeholder="Search Test Service" class="form-control" style="width: 35%;">
                        <b-button type="button" variant="primary">
                            {{countTestServices}} testservices selected
                        </b-button>
                    </div>
                </b-col>
                <div class="table-responsive mt-2">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th style="width: 7%;"></th>
                                <th style="width: 25%;" class="text-center">Testname</th>
                                <th style="width: 53%;" class="text-center">Method</th>
                                <th style="width: 15%;" class="text-center">Fee</th>
                            </tr>
                        </thead>
                    </table>
                    <simplebar data-simplebar style="max-height: 200px">
                        <div>
                            <table class="table table-centered table-bordered table-nowrap mb-0">
                                <tbody>
                                    <tr v-for="(list,index) in testservices" v-bind:key="index" :class="(list.is_checked) ? 'table-success' : (index == matchedRowIndex) ? 'table-warning' : ''" :id="'row-' + index">
                                        <td style="width: 7%;" class="text-center"> 
                                            <input class="form-check-input me-1" type="checkbox" v-model="list.is_checked">
                                        </td>
                                        <td style="width: 25%;" class="text-center fs-11">{{list.testname}}</td>
                                        <td style="width: 53%;" class="text-center fs-11">{{list.method}} <span v-if="list.method_short" class="text-muted">({{list.method_short}})</span></td>
                                        <td style="width: 15%;" class="text-center fs-11">{{list.fee}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </simplebar>
               </div>
            </BCol>
        </BRow>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import simplebar from 'simplebar-vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { InputLabel, Multiselect, simplebar, Amount, TextInput },
    props: ['laboratories'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: {},
            form: useForm({
                fee: null,
                lists: [],
                samples: [],
                option: 'save'
            }),
            filter: {
                keyword: null,
                sampletype: null
            },
            laboratory: null,
            searchTerm: null,
            matchedRowIndex: null,
            sampletypes: [],
            sampletype: null,
            testservices: [],
            selected: {},
            type: null,
            showModal: false
        }
    },
    watch: {
        sampletype(){
            this.testservices = [];
            this.fetchTest();
        },
        totalFee(newTotalFee) {
            this.form.fee = newTotalFee;
            this.$refs.testing.emitValue(this.form.fee);
        },
        "form.laboratory_type"(newVal){
            this.sampletype = null;
            this.sampletypes = [];
        }
    },
    computed: {
        totalFee() {
            const total = this.testservices.reduce((acc, item) => {
                return item.is_checked ? acc + parseFloat(item.fee_num) : acc;
            }, 0);
            return total.toFixed(2);
        },
        countTestServices() {
            return this.testservices.filter(item => item.is_checked).length;
        },
        checkedItems() {
            return this.testservices.filter(item => item.is_checked);
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        }, 
        checkSearchSample: _.debounce(function(string) {
            (string) ? this.fetchSample(string) : '';
        }, 300),
        submit(){
           const updatedItems= this.checkedItems.map(item => {
                return {
                    ...item,
                    lab: this.form.laboratory_type.name,
                    quantity: 1 // Add the new key
                };
            });
            this.$emit('success',updatedItems);
            this.hide();
        },
        amount(val){
            this.form.fee = val;
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
                    laboratory_type: this.form.laboratory_type.value,
                    type: 30,
                    keyword: code
                }
            })
            .then(response => {
                this.sampletypes = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchTest(code){
            axios.get('/services',{
                params: {
                    option: 'testservices',
                    laboratory_type: this.form.laboratory_type.value,
                    sampletype_id: this.sampletype,
                    keyword: code
                }
            })
            .then(response => {
                this.testservices = response.data.data;
            })
            .catch(err => console.log(err));
        },
        search() {
            const searchTerm = this.searchTerm.toLowerCase();
            const matchedIndex = this.testservices.findIndex(
                (l) => l.testname.toLowerCase().includes(searchTerm) || 
                l.method.toLowerCase().includes(searchTerm) || l.method_short.toLowerCase().includes(searchTerm)
            );
            if (matchedIndex !== -1 && searchTerm !== '') {
                this.matchedRowIndex = matchedIndex;

                const rowId = 'row-' + matchedIndex;
                const matchedRow = document.getElementById(rowId);

                if (matchedRow) {
                matchedRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                this.matchedRowIndex = null;
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.fee = null;
            this.$refs.multiselectS.clear();
            this.form.reset();
            this.form.clearErrors();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>