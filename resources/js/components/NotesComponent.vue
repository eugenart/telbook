<template>
    <div class="container-fluid">
        <div class="aside-bar" v-bind:class="{'bar-opened' : isopen}">
            <div class="row">
                <div class="col-1 all-notes"><p><a href="#" @click.prevent="chageWidth()">Содержание</a></p></div>
                <div class="col-10 note-notes">
                    <div id="accordion">
                        <div class="card mb-1">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <a class="btn white-text-a btn-link" @click.prevent="loadAllNotes(), chageWidth()">
                                        <i class="fas fa-users"></i> Полный список
                                    </a>
                                </h4>
                            </div>
                        </div>
                        <div class="card mb-1" v-for="note in parentNotes">
                            <div class="card-header" :id="'heading'+ note.id">
                                <h4 class="mb-0">
                                    <a class="btn white-text-a btn-link" data-toggle="collapse"
                                       :data-target="'#collapse'+ note.id"
                                       aria-expanded="true" :aria-controls="'collapse'+ note.id"
                                       @click="loadNotes(note.id)">
                                        <i class="fas fa-users"></i> {{ note.group_name }}
                                    </a>
                                </h4>
                            </div>

                            <div v-if="note.children.length > 1" :id="'collapse'+note.id" class="collapse hidden-part"
                                 :aria-labelledby="'headingOne'+note.id"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="#" class="btn list-group-item list-group-item-action"
                                           v-for="child in note.children"
                                           @click="loadNotes(child.id, child.name), chageWidth()">{{
                                            child.name }} <br>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden-block">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-12 col-md-12 table-all-width"
                     style="padding-left: 0 !important; padding-right: 50px !important;">
                    <div class="table-responsive p-0 tab-content">
                        <div class="tab-pane fade show active">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                <th width="23%">ФИО</th>
                                <th width="20%">Должность</th>
                                <th width="10%">Внешний номер</th>
                                <th width="10%">Внутренний номер</th>
                                <th width="15%">Email</th>
                                <th width="17%">Адрес</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-12 col-md-12 table-all-width"
                 style="padding-left: 0 !important; padding-right: 50px !important;">
                <div class="table-responsive p-0 tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active">
                        <table class="table table-hover" style="font-size: 13px !important">
                            <thead class="thead-light" id="toHide">
                            <th width="23%">ФИО</th>
                            <th width="20%">Должность</th>
                            <th width="10%">Внешний номер</th>
                            <th width="10%">Внутренний номер</th>
                            <th width="15%">Email</th>
                            <th width="17%">Адрес</th>
                            </thead>
                            <tbody v-for='groupN in groupsNames'>
                            <tr class="group-tr">
                                <td colspan="7">
                                    <h3>{{ groupN.gr_name }}
                                    </h3>
                                    <p class="in-line-p" v-if="groupN.tel.length > 0"><span style="color: white;"><b>Телефоны:</b></span>
                                        <span v-for="(phn, index) in groupN.tel">
                                            <a :href="'tel:'+cityTypes[groupN.tel[index].phonePrefix -1]+groupN.tel[index].phoneNumber">{{ cityTypes[groupN.tel[index].phonePrefix -1]}}
                                                {{ groupN.tel[index].phoneNumber }}</a>
                                            &nbsp;&nbsp;
                                        </span>
                                    </p>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <p class="in-line-p" v-if="groupN.email.length > 0"><span style="color: white;"><b>E-mail:</b></span>
                                        <span v-for="(ml, index) in groupN.email"><a
                                            :href="'mailto:'+groupN.email[index]">{{ groupN.email[index] }}</a>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    </p>
                                </td>
                            </tr>
                            <tr v-for="note in notes" v-if="note.group_info == groupN.gr_name" class="all_trs">
                                <td style="padding: 5px !important">{{ note.name }}</td>
                                <td style="padding: 5px !important">{{ note.position }}</td>
                                <td style="padding: 5px !important"><span v-for="(phn, index) in note.phone"
                                                                          v-if="note.phone">
                                    <a :href="'tel:'+cityTypes[note.phone[index].phonePrefix -1]+note.phone[index].phoneNumber">{{ cityTypes[note.phone[index].phonePrefix -1] }}
                            {{ note.phone[index].phoneNumber }}</a>
                                     <br>
                            </span>
                                </td>
                                <td style="padding: 5px !important"><span v-for="(iphn, index) in note.ip_phone">{{ note.ip_phone[index] }}<br></span>
                                </td>
                                <td style="padding: 5px !important"><span v-for="(ml, index) in note.email"><a
                                    :href="'mailto:'+note.email[index]">{{ note.email[index] }} </a><br></span></td>
                                <td style="padding: 5px !important"><a href="#" v-b-popover.click="note.address"
                                                                       title="Адрес">
                                    {{ note.building }}, <br> {{ note.room }} {{ roomTypes[note.room_type -1] }} </a>
                                </td>
                                <!--{{ note.address }}-->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                roomTypes: [
                    'кабинет',
                    'этаж'
                ],
                cityTypes: [
                    '+7 (8342)',
                    '+7 (83451)',
                    '+7 (83453)'
                ],
                notes: {},
                parentNotes: {},
                groupsNames: [],
                isopen: false
            }
        },

        methods: {
            loadNotes(id, name) {
                let names = [];
                axios.get('/api/note/' + id).then(({data}) => (this.notes = data,
                    data.forEach(function (item, i, data) {
                        names.push({
                            gr_name: item.group_info,
                            tel: item.group_tel,
                            email: item.group_mail
                        })
                    }),
                    this.groupsNames = this.onlyUnique(names)))
            },

            loadParentNotes() {
                axios.get('/api/note').then(({data}) => (this.parentNotes = data,
                        this.parentNotes = this.parentNotes.sort(this.SortByPriority)
                ))
            },

            onlyUnique(mass) {
                let seen = {};
                let unique = mass.filter(function (item) {
                    if (seen.hasOwnProperty(item.gr_name)) {
                        return false;
                    } else {
                        seen[item.gr_name] = true;
                        return true;
                    }
                })
                return unique;
            },

            SortByPriority(a, b) {
                var aName = a.priority
                var bName = b.priority
                return ((aName < bName) ? -1 : ((aName > bName) ? 1 : 0));
            },

            loadAllNotes() {
                let names = [];
                axios.get('/api/all_groups').then(({data}) => (this.notes = data,
                    data.forEach((item, i, data) => {
                        names.push({
                            gr_name: item.group_info,
                            tel: item.group_tel,
                            email: item.group_mail,
                            priority: item.parent_priority * 10 + item.priority,
                        })
                    }),
                    this.groupsNames = this.onlyUnique(names),
                    this.groupsNames = this.groupsNames.sort(this.SortByPriority),
                    console.log(this.groupsNames)))
            },

            chageWidth() {
                this.isopen = !this.isopen;
            }
        },

        created() {
            this.loadAllNotes();
            this.loadParentNotes();
        }
    }


</script>

<style scoped>

    td.all_trs {
        padding: 5px !important;
    }

    .hidden-block {
        font-size: 13px;
        display: none;
        position: fixed;
        top: 76.22px;
        left: 0px;
        width: 100%;
        z-index: 100;
        padding-left: 15px;
        padding-right: 15px;
    }

    .aside-bar {
        position: fixed;
        height: 100%;
        right: 0;
        top: 0;
        background-color: white;
        /*background-color: rgba(4, 30, 66, 1);*/
        width: 50px;
        z-index: 500;
        transition-duration: 1s;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .bar-opened {
        width: 30%;
        transition-duration: 1s;
    }

    .all-notes {
        height: 100%;
    }

    .all-notes p {
        writing-mode: tb-rl;
        height: 100%;
        text-align: center;
    }

    .all-notes p a {
        display: block;
        margin: auto;
        color: rgba(4, 30, 66, 1);
        float: left;
        height: 100%;
        text-align: center;
    }

    .note-notes {
        float: left;
        padding-top: 40px;
        display: flex;
        flex-direction: column;
    }

    .white-text-a {
        color: rgba(4, 30, 66, 0.9) !important;
        padding: 0 10px 0 10px;
        font-size: 14px;
    }

    .group-tr {
        background-color: rgba(4, 30, 66, 0.9);
        color: white;

    }

    .group-tr:hover {
        background-color: rgba(4, 30, 66, 0.9);
        color: white;
    }

    .group-tr td {
        padding: 0.25rem;
    }

    .group-tr td h3 {
        margin: 0;
    }

    .in-line-p {
        display: inline;
        width: auto;
    }

    .group-tr:hover, .group-tr:hover a {
        color: white;
    }

    .group-tr a {
        color: white;
    }

    .card-header {
        background-color: white;
        border: none;
        padding: 0px;
    }

    .card-header button {
        color: white;
    }

    .card, .list-group-item {
        border: none;
        padding: 0;
        padding-left: 15px;
        font-size: 14px !important;
        padding-bottom: 3px;
        font-weight: lighter;
        text-decoration: underline !important;
    }

    .btn {
        font-size: 16px;
        font-weight: bolder;
    }

    .card-body {
        padding: 0.5rem;
    }

    .card {
        border: none;
    }

    .we-have-mistake {
    }


    @media (max-width: 1441px) {
        .white-text-a {
            font-size: 14px;
        }
    }


    @media (max-width: 1281px) {
        .card-header {
            background-color: white;
            border: none;
        }

        .card {
            border: none;
        }

        .list-group-item {
            border: none;
            padding: 0;
            padding-left: 15px;
            padding-bottom: 3px;
            text-decoration: underline !important;
        }

        .white-text-a {
            color: rgba(4, 30, 66, 0.9) !important;
            padding: 0 10px 0 10px;
            font-size: 14px;
        }

    }

</style>
