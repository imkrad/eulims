<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" hide-footer hide-header title="Cancel Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="modal-body">
            <div class="mt-2">
                <h4 class="mb-1 text-center">Edit Information for the Quotation</h4>
                <p class="text-primary mb-4 fs-11 text-center">Upon confirming, you cannot add samples, analyses, or edit information for the quotation.</p>
                <!-- <p class="text-muted mb-4">Please double-check all data to avoid cancellation or updating of the data.</p> -->
                <div class="customform">
                    <h6 class="fw-semibold" style="font-size: 11px; margin-top: 12px;">1. VALIDITY DATE  </h6>
                    <BCol lg="12" class="mt-2">
                        <!-- <InputLabel for="due" value="Valid Until" :message="form.errors.due_at"/> -->
                        <TextInput v-model="form.due_at" type="date" class="form-control" autofocus placeholder="Please enter email" autocomplete="email" required @input="handleInput('due_at')" :light="true"/>
                    </BCol>
                    <h6 class="fw-semibold" style="font-size: 11px; margin-top: 15px;">2. TERMS AND CONDITION</h6>
                    <ul class="fs-11" style="margin-left: -20px;">
                        <li v-for="(list,index) in terms" v-bind:key="index">{{list}}</li>
                    </ul>
                    <button @click="openTerm()" class="btn btn-sm btn-light mt-n1">Add Terms and Condition</button>
                    <h6 class="fw-semibold" style="font-size: 11px; margin-top: 12px;">3. CONFIRMATION  </h6>
                    <BCol lg="12" class="mt-2">
                        <!-- <InputLabel for="due" value="Please type CONFIRM to continue."/> -->
                        <TextInput v-model="keyword" type="text" placeholder="Please type CONFIRM to continue" class="form-control" :light="true"/>
                    </BCol>
                    <div class="hstack gap-2 justify-content-center mt-4">
                        <button @click="hide()" class="btn btn-light btn-md" type="button">
                            <div class="btn-content"> Close</div>
                        </button>
                        <button @click="submit()" :disabled="keyword !== 'CONFIRM'" class="btn btn-primary">Submit</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </b-modal>
    <Term @new="addNew" ref="term"/>
</template>
<script>
import Term from './Term.vue';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    components: { InputLabel, TextInput, Term },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
               id: null,
               status_id: 15,
               due_at: null,
               terms: null,
               option: 'Confirm'
            }),
            // terms: [
            //     'Payment Method: Cheque payment should be paid to DOST IX;',
            //     'DOST IX Trust Fund 1952101052 Landbank of the Philippines.',
            //     'Cash payment should be made directly to the cashier or deposit to DOST IX account.',
            //     'This quotation is valid only until '+this.form.due_at, 
            // ],
            keyword: null,
            showModal: false
        }
    },
    computed: {
        terms() {
        return [
            'DOST-IX RSTL implements Payment First Policy.',
            'Payment Method: Cheque payment should be paid to DOST IX;',
            'DOST IX Trust Fund 1952101052 Landbank of the Philippines.',
            'Cash payment should be made directly to the cashier or deposit to DOST IX account.',
            'This quotation is valid only until ' + (this.form.due_at ? this.form.due_at : 'N/A'),
        ];
        }
    },
    methods: { 
        show(id){
            this.form.id = id;
            this.showModal = true;
        },
        addNew(data){
            this.terms.push(data);
        },
        openTerm(){
            this.$refs.term.show();
        },
        submit(){
            this.form.terms = this.terms;
            this.form.put('/quotations/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('selected',response.props.flash.data.data);
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