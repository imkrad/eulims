<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="Add Group" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="6" class="mt-1">
                    <InputLabel for="days" value="Sampling Days"/>
                    <TextInput v-model="form.days" type="text" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="days" value="Date"/>
                    <TextInput v-model="form.date" type="date" class="form-control" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0"><hr class="text-muted"/></BCol>
            </BRow>
        </form>
               
        <b-col lg>
            <div class="input-group mb-3">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="searchTerm" @input="search" placeholder="Search Test Service" class="form-control" style="width: 35%;">
                <b-button type="button" @click="openTestservice()" variant="primary">
                    <i class="ri-add-circle-fill align-bottom me-1"></i>Add Testservice
                </b-button>
            </div>
        </b-col>
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light thead-fixed">
                <tr class="fs-11">
                    <th>#</th>
                    <th width="40%">Testname</th>
                    <th class="text-center" width="20%">Fee</th>
                    <th class="text-center" width="15%">Quantity</th>
                    <th class="text-center" width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in form.lists" v-bind:key="index">
                    <td>{{index+1}}</td>
                    <td>
                        <h5 class="fs-13 mb-0 fw-semibold text-primary">{{list.testname}}</h5>
                        <p class="fs-13 text-muted mb-0">{{list.lab}}</p>
                    </td>
                    <td class="text-center">{{list.fee}}</td>
                    <td class="d-flex justify-content-center align-items-center">
                        <input type="text" v-model="list.quantity" class="form-control" style="text-align: center; width: 50px;">
                    </td>
                    <td class="text-center">{{formatMoney(list.quantity*parseFloat(list.fee_num))}}</td>
                </tr>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <td class="text-end" colspan="4">Total</td>
                    <td class="text-center">0</td>
                </tr>
            </tfoot>
        </table>
    
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" block>Submit</b-button>
        </template>
    </b-modal>
    <Testservice :laboratories="laboratories" @success="addItems" ref="testservice"/>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import simplebar from 'simplebar-vue';
import Testservice from './Testservice.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
export default {
    components: { InputLabel, Multiselect, simplebar, Amount, TextInput, Testservice },
    props: ['laboratories'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: {},
            form: useForm({
                tsr_id: null,
                days: null,
                date: null,
                lists: [],
                option: 'group'
            }),
            selected: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        }, 
        submit(){
            this.form.tsr_id = this.selected.id;
            this.form.post('/analyses',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.hide();
                },
            });
        },
        addItems(data){
            console.log(data);
            this.form.lists.push(...data);
        },
        openTestservice(){
            this.$refs.testservice.show();
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.form.reset();
            this.showModal = false;
        }
    }
}
</script>