<template>
    <PageHeader title="Industries" pageTitle="List" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">

            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="filter.keyword" placeholder="Search Program" class="form-control" style="width: 100px;">
                        <Multiselect class="white" style="width: 15%;" :options="subs" v-model="filter.sub" label="name" :searchable="true" placeholder="Select Sub" />
                        <Multiselect class="white" style="width: 15%;" :options="statuses" v-model="filter.status" label="name" :searchable="true" placeholder="Select Status" />
                        <Multiselect class="white" style="width: 15%;" :options="programs" v-model="filter.program" label="name" :searchable="true" placeholder="Select Program" />
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
                            <th style="width: 50%;">Name</th>
                            <th style="width: 20%;" class="text-center">Is main</th>
                            <th style="width: 20%;" class="text-center">Status</th>
                            <th style="width: 7%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td> {{ (meta.current_page - 1) * meta.per_page + index + 1 }}</td>
                            <td>{{list.name}}</td>
                            <td class="text-center">
                                <span v-if="list.is_main" class="badge bg-info-subtle text-info">Yes</span>
                                <span v-else class="badge bg-primary-subtle text-primary">No</span>
                            </td>
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
            <Create @success="fetch()" :programs="programs" ref="create"/>
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
    props: ['programs'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: { 
                keyword: null,
                program: null,
                status: null,
                sub: null
            },
            statuses: [
                {value: 1, name: 'Active'},
                {value: 0, name: 'Inactive'}
            ],
            subs: [
                {value: 1, name: 'Main-programs'},
                {value: 0, name: 'Sub-programs'}
            ],
            index: null
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.program"(newVal){
            this.fetch();
        },
        "filter.status"(newVal){
            this.fetch();
        },
        "filter.sub"(newVal){
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
                    program: this.filter.program,
                    status: this.filter.status,
                    sub: this.filter.sub,
                    count: ((window.innerHeight-350)/52),
                    option: 'industries'
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