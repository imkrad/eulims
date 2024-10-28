<template>
    <hr class="text-muted"/>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-10">
                    <th style="width: 4%;"></th>
                    <th style="width: 20%;">Name</th>
                    <th style="width: 25%;" class="text-center">Contact no.</th>
                    <th style="width: 16%;" class="text-center">Created At</th>
                    <th style="width: 15%;" class="text-center">Updated At</th>
                    <th style="width: 10%;" class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="index" class="fs-12">
                    <td>{{ index + 1 }}.</td>
                    <td>{{list.name}}</td>
                    <td class="text-center">{{list.contact_no}}</td>
                    <td class="text-center">{{list.created_at}}</td>
                    <td class="text-center">{{list.updated_at}}</td>
                    <td class="text-end">
                        <b-button @click="openEdit(list)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                            <i class="ri-pencil-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <Conforme ref="conforme"/>
</template>
<script>
import Conforme from '../Modals/Conforme.vue';
export default {
    components: { Conforme },
    props: ['lists'],
    data(){
        return {
            currentUrl: window.location.origin,
        }
    },
    methods: {
        openEdit(list){
            this.$refs.conforme.show(list);
        }
    }
}
</script>