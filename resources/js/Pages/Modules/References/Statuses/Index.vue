<template>
    <PageHeader title="Statuses" pageTitle="List" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">

            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="filter.keyword" placeholder="Search Status" class="form-control" style="width: 100px;">
                        <Multiselect class="white" style="width: 15%;" :options="types" v-model="filter.type" label="name" :searchable="true" placeholder="Select Type" />
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
                            <th></th>
                            <th style="width: 25%;">Name</th>
                            <th style="width: 15%;" class="text-center">Type</th>
                            <th style="width: 20%;" class="text-center">Color</th>
                            <th style="width: 15%;" class="text-center">Others</th>
                            <th style="width: 15%;" class="text-center">Status</th>
                            <th style="width: 7%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td> {{ (meta.current_page - 1) * meta.per_page + index + 1 }}</td>
                            <td>{{list.name}}</td>
                            <td class="text-center">{{list.type}}</td>
                            <td class="text-center">{{list.color}}</td>
                            <td class="text-center">{{list.others}}</td>
                            <td class="text-center">
                                <span v-if="list.is_active" class="badge bg-success">Active</span>
                                <span v-else class="badge bg-danger">Inactive</span>
                            </td>
                            <td class="text-end">
                                <b-button @click="openUpdate(list,index)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                    <i class="ri-pencil-fill align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                    </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
            <Create @success="fetch()" ref="create"/>
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import Create from './Create.vue';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Create, Multiselect},
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: { 
                keyword: null,
                type: null
            },
            index: null,
            types: ['Progress','Ongoing','Qualifier']
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.type"(newVal){
            this.fetch();
        },
        '$page.props.flash' : {
            deep: true,
            handler(val = null) {
                if(val.status){
                    this.lists[this.index] = val.data.data;
                }
            }
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
                    count: ((window.innerHeight-350)/52),
                    option: 'statuses'
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
        openCreate(){
            this.$refs.create.show();
        },
        openUpdate(data,index){
            this.index = index;
            this.$refs.create.update(data);
        }
    }
}
</script>