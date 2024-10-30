<template>
    <Head title="Dashboard"/>
    <PageHeader title="Dashboard" pageTitle="Cashier" />
    <b-row>
        <div class="col-md-12">
            <div class="row g-3">
                
                

            </div>
        </div>
    </b-row>
</template>
<script>
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    props: ['dropdowns'],
    components: { PageHeader },
    methods: {
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    }
}
</script>