<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Course' : 'Create Course'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the course information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel value="Name" :message="form.errors.name"/>
                                <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true" />
                            </BCol>
                            <BCol lg="12" class="mt-0">
                                <InputLabel value="Shortcut" :message="form.errors.shortcut"/>
                                <TextInput v-model="form.shortcut" type="text" class="form-control" placeholder="Please enter shortcut" @input="handleInput('shortcut')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Abbreviation" :message="form.errors.abbreviation"/>
                                <TextInput v-model="form.abbreviation" type="text" class="form-control" placeholder="Please enter abbreviation" @input="handleInput('abbreviation')" :light="true" />
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
    props: ['regions'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                shortcut: null,
                abbreviation: null,
                others: null,
                option: 'course'
            }),
            provinces: [],
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.showModal = true;
        },
        update(data){
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.shortcut = data.shortcut;
            this.form.abbreviation = data.abbreviation;
            this.form.others = data.others;
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/management/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                    },
                });
            }else{
                this.form.post('/management',{
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