<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Add Terms and Condition" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-1">
                    <InputLabel for="sampletype" value="Name"/>
                    <TextInput v-model="name" type="text" class="form-control" required @input="handleInput('name')" :light="true"/>
                </BCol>
            </BRow>
        </form>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: {},
            name: null,
            showModal: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        }, 
        submit(){
            this.$emit('new',this.name);
            this.showModal = false;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.name = null;
            this.showModal = false;
        }
    }
}
</script>