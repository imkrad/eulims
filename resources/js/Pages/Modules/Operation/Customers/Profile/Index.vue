<template lang="">
    <Head title="Customer Profile"/>
    <Top :customer="customer"/>
    <b-row class="g-3">
        <Sidebar :wallet="customer.data.wallet"/>
        <b-col lg="8">
            <b-row>
                <Count :customer="customer"/>
                <b-col lg="12 mt-n2">
                    <b-card no-body>
                        <b-card-body style="height: calc(100vh - 385px); overflow: auto;">
                            <BTabs nav-class="nav-pills nav-custom nav-custom-light" small pills>
                                <BTab title="TSR's">
                                    <Lists :id="customer.data.id"/>
                                </BTab>
                                <BTab title="Conformes">
                                    <Conforme :lists="customer.data.conformes"/>
                                </BTab>
                            </BTabs>
                           
                        </b-card-body>
                    </b-card>
                </b-col>
            </b-row>
        </b-col>
    </b-row>
</template>
<script>
import Top from './Top.vue';
import Lists from './Lists.vue';
import Count from './Count.vue';
import Sidebar from './Sidebar.vue';
import Conforme from './Conforme.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props:['customer'],
    components: { PageHeader, Top, Count, Sidebar, Lists,Conforme },
}
</script>