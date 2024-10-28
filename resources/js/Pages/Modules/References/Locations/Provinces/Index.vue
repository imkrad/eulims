<template>
    <PageHeader title="Provinces" pageTitle="Location" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="filter.keyword" placeholder="Search Province" class="form-control" style="width: 100px;">
                        <Multiselect class="white"
                        style="width: 15%;"
                        :options="regions" 
                        v-model="filter.region" 
                        label="name"
                        :searchable="true" 
                        placeholder="Select Region" />
                        <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                            <i class="bx bx-refresh search-icon"></i>
                        </span>
                        <b-button type="button" variant="primary" @click="openCreate()">
                            <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                        </b-button>
                    </div>
                </b-col>
            </b-row>
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th style="width: 3%;"></th>
                            <th style="width: 20%;">Code</th>
                            <th style="width: 28%;" class="text-center">Name</th>
                            <th style="width: 20%;" class="text-center">Old Name</th>
                            <th style="width: 20%;" class="text-center">Region</th>
                            <th style="width: 7%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index" :class="[(list.is_active == 0) ? 'table-warnings' : '']">
                            <td> {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.</td>
                            <td> {{list.code}}</td>
                            <td class="text-center">{{list.name}}</td>
                            <td class="text-center" v-if="list.old_name">{{list.old_name}}</td>
                            <td class="text-center" v-else><span class="text-muted">Old name not specified</span></td>
                            <td class="text-center">{{list.region.region}} <span class="text-muted">({{ list.region.name }})</span></td>
                            <td class="text-end">
                                <b-button @click="openUpdate(list,index)" variant="soft-primary" v-b-tooltip.hover title="Edit" size="sm" class="edit-list">
                                    <i class="ri-pencil-fill align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
            <Update :regions="regions" ref="update"/>
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import Update from './Update.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect, Update },
    props: ['regions'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null,
                region: null
            },
            index: null
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.region"(newVal){
            this.fetch();
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/references';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    region: this.filter.region,
                    count: ((window.innerHeight-350)/52),
                    option: 'provinces'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openUpdate(data,index){
            this.index = index;
            this.$refs.update.show(data);
        },
        openCreate(){
            this.$refs.update.create();
        },
        refresh(){
            this.filter.keyword = null;
            this.fetch();
        }
    }
}
</script>
<style scoped>
.multiselect-search {
  background: #fff !important;
}
</style>