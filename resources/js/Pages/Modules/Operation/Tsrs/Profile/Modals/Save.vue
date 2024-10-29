<template>
    <b-modal v-model="showModal" hide-footer hide-header title="Cancel Request" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="modal-body">
            <div class="mt-2">
                <h4 class="mb-4 text-center">Confirm TS Request</h4>
                <p class="text-primary mb-4 text-center">Upon confirming, you cannot add samples, analyses, or edit information for the TS Request.</p>
                <!-- <p class="text-muted mb-4">Please double-check all data to avoid cancellation or updating of the data.</p> -->
                <div class="customform">
                    <BRow>
                        <BCol lg="12" class="mt-2">
                            <InputLabel for="due" value="Report Due" :message="form.errors.due_at"/>
                            <TextInput v-model="form.due_at" type="date" class="form-control" autofocus placeholder="Please enter email" autocomplete="email" required @input="handleInput('due_at')" :light="true"/>
                        </BCol>
                        <BCol lg="12" class="mt-2">
                            <InputLabel for="due" value="Please type CONFIRM to continue."/>
                            <TextInput v-model="keyword" type="text" class="form-control" :light="true"/>
                        </BCol>
                        <template v-if="form.industry == 'Government'">
                            <BCol lg="12" class="mt-3"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                            <BCol lg="8" style="margin-top: 13px; margin-bottom: -12px;" class="fs-12" :class="(form.errors.is_government) ? 'text-danger' : ''">Has Memorandum of Agreement?</BCol>
                            <BCol lg="4" style="margin-top: 13px; margin-bottom: -12px;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-3">
                                            <input type="radio" id="customRadio1" class="custom-control-input me-2" :value="true" v-model="form.is_government">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-3">
                                            <input type="radio" id="customRadio2" class="custom-control-input me-2" :value="false" v-model="form.is_government">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                                        </div>
                                    </div>
                                </div>
                            </BCol>
                            <BCol lg="12"><hr class="text-muted mt-2"/></BCol>
                            <BCol lg="12" class="mt-n2">
                                <div class="alert alert-warning fs-10 text-center" role="alert">It will set the TSR status to <b>Ongoing</b> and the payment status to <b>Contract</b>.</div>
                            </BCol>
                        </template>
                    </Brow>
                    <div class="hstack gap-2 justify-content-center mt-4">
                        <button @click="hide()" class="btn btn-light btn-md" type="button">
                            <div class="btn-content"> Close</div>
                        </button>
                        <button @click="submit()" :disabled="keyword !== 'CONFIRM'" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                status_id: 2,
                due_at: null,
                is_government: null,
                industry: null,
                option: 'Confirm'
            }),
            keyword: null,
            showModal: false
        }
    },
    methods: { 
        show(id,industry){
            this.form.id = id;
            this.form.industry = industry;
            this.showModal = true;
        },
        submit(){
            this.form.put('/tsrs/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('selected',response.props.flash.data.data);
                    this.hide();
                    window.open('/samples?option=allsampleqr&id='+response.props.flash.data.data.qr);
                },
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>