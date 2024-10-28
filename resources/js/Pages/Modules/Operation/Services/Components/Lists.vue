<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Test Service" class="form-control" style="width: 65%;">
                <select v-model="filter.laboratory" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 100px;">
                    <option :value="null" selected>Select Laboratory</option>
                    <option :value="list.value" v-for="list in dropdowns.types" v-bind:key="list.id">{{list.name}}</option>
                </select>
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="openCreate">
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
                    <th style="width: 20%;">Laboratory</th>
                    <th style="width: 20%;" class="text-center">Sampletype</th>
                    <th style="width: 20%;" class="text-center">Testname</th>
                    <th style="width: 11%;" class="text-center">Method</th>
                    <th style="width: 11%;" class="text-center">Fee</th>
                    <th style="width: 7%;" class="text-center">Status</th>
                    <th style="width: 8%;" ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td class="text-center"> 
                        {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.type.name}} <span class="text-muted fs-11">({{list.laboratory.name}})</span></h5>
                        <p class="fs-12 text-muted mb-0">{{list.laboratory.member.name}}</p>
                    </td>
                    <td class="text-center fs-12">{{list.sampletype.name}}</td>
                    <td class="text-center fs-12">{{list.testname.name}}</td>
                    <td class="text-center fs-12">{{ (list.method.method.short) ? list.method.method.short: list.method.method.name}}</td>
                    <td class="text-center fs-12">{{list.method.fee}}</td>
                    <td class="text-center">
                        <span v-if="list.is_active" class="badge bg-success">Active</span>
                        <span v-else class="badge bg-danger">Inactive</span>
                    </td>
                    <td class="text-end">
                        <b-button @click="openFee(list.id,list.fees,list.laboratory_id)" variant="soft-warning" class="me-1" v-b-tooltip.hover title="Add Fee" size="sm">
                            <i class="ri-add-circle-fill align-bottom"></i>
                        </b-button>
                        <b-button @click="openProfile(list,index)" variant="soft-info" v-b-tooltip.hover title="View" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create @message="checkSearchStr" :laboratory="laboratory" :dropdowns="dropdowns" ref="create"/>
    <Fee ref="fee"/>
    <Profile ref="profile"/>
</template>
<script>
import _ from 'lodash';
import Fee from '../Modals/Fee.vue';
import Profile from '../Modals/Profile.vue';
import Create from '../Modals/Create.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Create, Fee, Profile },
    props: ['dropdowns','laboratory'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            filter: {
                keyword: null,
                laboratory: null
            }
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        },
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/services';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    laboratory: this.filter.laboratory,
                    count: Math.floor((window.innerHeight-350)/58),
                    option: 'lists'
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
        openFee(id,fee,lab){
            this.$refs.fee.show(id,fee,lab);
        },
        openProfile(data){
            this.$refs.profile.show(data);
        }
    }
}
</script>