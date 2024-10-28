<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Status' : 'Create Status'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the privilege information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel value="Name" :message="form.errors.name"/>
                                <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true" />
                            </BCol>
                            <BCol lg="12" class="mt-0">
                                <InputLabel value="Type" :message="form.errors.type"/>
                                <Multiselect :options="types" v-model="form.type" label="name" placeholder="Select Type" />
                            </BCol>
                            <BCol lg="6" class="mt-1">
                                <InputLabel value="Color" :message="form.errors.color"/>
                                <TextInput v-model="form.color" type="text" class="form-control" placeholder="Please enter color" @input="handleInput('color')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-1">
                                <InputLabel value="Others" :message="form.errors.others"/>
                                <TextInput v-model="form.others" type="text" class="form-control" placeholder="Please enter others" @input="handleInput('others')" :light="true" />
                            </BCol>
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
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                type: null,
                color: null,
                others: null,
                option: 'status'
            }),
            types: ['Progress','Ongoing','Qualifier'],
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
            this.form.type = data.type;
            this.form.color = data.color;
            this.form.others = data.others;
            this.form.is_active = (data.is_active) ? true : false;
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