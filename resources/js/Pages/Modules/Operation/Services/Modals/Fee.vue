<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :size="fees.length > 0 ? 'lg' : ''" title="Add Fee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol :lg="(fees.length > 0) ? '8' : '12'">
                    <InputLabel for="name" value="Description" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" :light="true"/>
                </BCol>   
                <BCol :lg="(fees.length > 0) ? '4' : '12'" :class="(fees.length > 0) ? '' : 'mt-n1'">
                    <InputLabel for="short" value="Fee" :message="form.errors.fee"/>
                    <Amount @amount="amount" ref="testing" :readonly="false"/>
                </BCol> 
            </BRow>     
        </form>      
        <div class="mt-2" v-if="fees.length > 0">
            <hr class="text-muted"/>
            <b-list-group>
                <b-list-group-item v-for="(list,index) in fees" v-bind:key="index">
                    <i class="ri-arrow-drop-right-line fs-16 me-2"></i> {{list.name}} <span class="float-end mt-1">{{list.fee}}</span>
                </b-list-group-item>
            </b-list-group>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="!fee" @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Amount from '@/Shared/Components/Forms/Amount.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Amount },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                fee: null,
                laboratory_id: null,
                is_additional: true,
                description: 'n/a',
                option: 'fee'
            }),
            fees: [],
            showModal: false
        }
    },
    methods: { 
        show(id,fees,lab){
            this.form.id = id;
            this.fees = fees;
            this.form.laboratory_id = lab;
            this.showModal = true;
        },
        amount(val){
            this.form.fee = val;
        },
        submit(){
            this.form.post('/services',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>