<template lang="">
    <Head title="Quotation"/>
    <div class="auth-page-wrapper d-flex min-vh-100">
        <div class="auth-page-content">
            <Top :selected="quotation.data" :analyses="analyses.data"/>
            <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
                <div class="file-manager-content w-100 p-4 pb-0" ref="box" style="margin-left: 20px;">
                    <Main :selected="quotation.data" :services="services" :analyses="analyses.data"/> 
                </div>
                <div class="file-manager-sidebar" style="margin-right: 20px; overflow-y: auto; overflow-x: hidden;">
                    <Sidebar :selected="quotation.data"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Top from './Components/Top.vue';
import simplebar from "simplebar-vue";
import Main from './Components/Main.vue';
import Sidebar from './Components/Sidebar.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    layout: null,
    components: { PageHeader, simplebar, Top, Sidebar, Main },
    props: ['quotation','services','analyses'],
}
</script>
<style scoped>
.auth-page-wrapper .auth-page-content {
    padding-bottom: 0px;
  width: 100%;
  overflow: hidden;
  background-color: #f3f3f9;
}
.file-manager-sidebar {
  min-width: 24%;
  max-width: 24%;
  height: calc(100vh - 92px);
}
</style>
