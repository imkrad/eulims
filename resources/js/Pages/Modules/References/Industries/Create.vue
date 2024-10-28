<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Program' : 'Create Program'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the program information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel value="Name" :message="form.errors.name"/>
                                <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true" />
                            </BCol>
                            <BCol lg="12" class="mt-0">
                                <InputLabel value="Program" :message="form.errors.type"/>
                                <Multiselect :options="programs" v-model="form.program_id" label="name" placeholder="Select Program" />
                            </BCol>
                            <BCol lg="8"  style="margin-top: 13px; margin-bottom: -12px;" class="fs-12" :class="form.errors.is_sub ? 'text-danger' : ''">Is a sub program?</BCol>
                            <BCol lg="4"  style="margin-top: 13px; margin-bottom: -12px;">
                               <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-3">
                                            <input type="radio" id="customRadio1" class="custom-control-input me-2" :value="true" v-model="form.is_sub">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-3">
                                            <input type="radio" id="customRadio2" class="custom-control-input me-2" :value="false" v-model="form.is_sub">
                                            <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                                        </div>
                                    </div>
                                </div>
                            </BCol>
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                        </BRow>
                    </div>    
                </BCol>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: {InputLabel, TextInput, Multiselect },
    props: ['programs'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                others: 'n/a',
                is_sub: null,
                program_id: null,
                option: 'program'
            }),
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.editable = false;
            this.showModal = true;
        },
        update(data){
            this.form.reset();
            this.form.id = data.id
            this.form.name = data.name;
            this.form.others = data.others;
            this.form.is_sub =(data.is_sub) ? true : false;
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/references/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                    },
                });
            }else{
                this.form.post('/references',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                        this.$emit('success',true);
                    },
                });
            }
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>