<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Dropdown' : 'Create Dropdown'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the province information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel value="Name" :message="form.errors.name"/>
                                <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Classification" :message="form.errors.classification"/>
                                <TextInput readonly v-model="form.classification" type="text" class="form-control" placeholder="Please enter classification" @input="handleInput('classification')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Type" :message="form.errors.type"/>
                                <TextInput readonly v-model="form.type" type="text" class="form-control" placeholder="Please enter type" @input="handleInput('type')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Color" :message="form.errors.color"/>
                                <TextInput v-model="form.color" type="text" class="form-control" placeholder="Please enter color" @input="handleInput('color')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Others" :message="form.errors.others"/>
                                <TextInput v-model="form.others" type="text" class="form-control" placeholder="Please enter others" @input="handleInput('others')" :light="true" />
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
    props: ['regions','type'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                classification: null,
                type: this.type,
                color: null,
                otherS: null,
                option: 'dropdown'
            }),
            provinces: [],
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(classification){
            this.form.reset();
            this.form.classification = classification;
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