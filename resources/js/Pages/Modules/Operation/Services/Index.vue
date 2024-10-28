<template lang="">
    <Head title="Test Services"/>
    <PageHeader title="Test Services" pageTitle="Menu" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <Lists :laboratory="laboratory" :dropdowns="dropdowns" ref="lists"/>
        </div>
    </div>
</template>
<script>
import Lists from './Components/Lists.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['dropdowns','laboratory'],
    components: { PageHeader, Lists }
}
</script>