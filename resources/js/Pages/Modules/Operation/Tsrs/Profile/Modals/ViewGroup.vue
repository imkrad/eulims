<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="Add Group" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <template v-for="(items, index) in groupedData" :key="index">
            <table class="table table-nowrap align-middle mb-2">
                <thead class="table-light thead-fixed">
                    <tr class="fs-11">
                        <th width="40%">Testname</th>
                        <th class="text-center" width="20%">Quantity</th>
                        <th class="text-center" width="20%">Fee</th>
                        <th class="text-center" width="20%">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" class="fs-13 fw-semibold text-primary">{{items[0].typeName}}</td>
                        <td colspan="2">
                           <button @click="openGenerate(items[0])" class="btn btn-soft-info btn-sm float-end" type="button">
                                <div class="btn-content"><i class="ri-file-list-3-line align-middle"></i> Generate TSR </div>
                            </button>
                        </td>
                    </tr>
                    <tr v-for="item in items[0].items" :key="item.id">
                        <td>
                            <h5 class="fs-12 mb-0">{{item.testservice.testname.name}}</h5>
                        </td>
                        <td class="text-center">{{item.quantity}} </td>
                        <td class="text-center">{{item.fee}} </td>
                        <td class="text-center">{{item.total}}</td>
                    </tr>
                </tbody>
            </table>
        </template>


        <!-- <div v-for="(items, labType) in groupedData" :key="labType">
        <h3>{{ labType }}</h3>
            <ul>
                <li v-for="item in items" :key="item.id">
                Test Name: {{ item.testservice.testname.name }},
                Fee: {{ item.fee }},
                Quantity: {{ item.quantity }},
                Total: {{ item.total }}
                </li>
            </ul>
        </div> -->
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
        </template>
    </b-modal>
    <Generate ref="generate"/>
</template>
<script>
import _ from 'lodash';
import Generate from '../Modals/Generate.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    components: { Generate },
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
            id: null,
            customer: null,
            payment: null,
            selected: null,
            showModal: false
        }
    },
    computed: {
       groupedData() {
            return this.selected.items.reduce((acc, item) => {
                // Get the type name and type ID
                const typeId = item.testservice.type.id;
                const typeName = item.testservice.type.name;
                
                // Find the existing group with the same typeId
                let group = acc.find(group => group[0].typeId === typeId);
                
                // If the group doesn't exist, create a new one
                if (!group) {
                    group = [{
                        typeId: typeId,
                        typeName: typeName,
                        customer: this.customer.name,
                        customer_id: this.customer.id,
                        conformes: this.customer.conformes,
                        or_number: this.payment.or_number,
                        discount_id: this.payment.discount_id,
                        payment_id: this.payment.payment_id,
                        collection_id: this.payment.collection_id,
                        status_id: this.payment.status_id,
                        tsr_id: this.id,
                        items: []
                    }];
                    acc.push(group);
                }
                
                // Add the item to the appropriate group's items array
                group[0].items.push(item);
                
                return acc;
            }, []);
        }
    },
    methods: { 
        show(data,customer,payment,id){
            this.id = id;
            this.payment = payment;
            this.customer = customer;
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
        openGenerate(data){
            this.$refs.generate.show(data);
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
        }
    }
}
</script>