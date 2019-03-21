<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Пользователи</h4>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover" v-show="users.length > 0">
                            <thead>
                            <tr>
                                <th width="35%" scope="col">Название</th>
                                <th width="35%" scope="col">Группа</th>
                                <th width="30%" scope="col">Редактирование</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="users" v-for="user in users" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td><p v-for="group in groups" v-if='user.group_list && group.id == user.group_list'>
                                    {{group.name }}</p></td>
                                <td>
                                    <button class="btn btn-warning" @click="modalForChangeUser(user)">
                                        Редактировать
                                    </button>
                                    <button class="btn btn-danger" @click="deleteBuilding(user.id, user.name)">
                                        x
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Изменение пользователя</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input placeholder="Название" v-model="form.name" type="text" name="list_group"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input placeholder="Email" v-model="form.email" type="text" name="email"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input placeholder="Новый пароль" v-model="form.password" type="text" name="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>
                            <div class="form-group">
                                <model-list-select :list="groups" option-value="id" option-text="name" required
                                                   v-model="form.group_list"
                                                   placeholder="Выберите институт/факультет/подразделение">
                                </model-list-select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Сохранить изменения
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {ModelSelect, ModelListSelect},
        data() {
            return {
                users: {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    group_list: ''
                }),
                groups: []
            }
        },

        methods: {
            updateUser(id) {
                this.$Progress.start();
                this.form.put('/api/user/' + this.form.id)
                    .then(() => {
                        toast({
                            type: 'success',
                            title: 'Пользователь обновлен'
                        });
                        $('#UserModal').modal('hide');
                        Fire.$emit('UpdateUserTable');
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            }
            ,

            modalForChangeUser(user) {
                this.form.reset();
                $('#UserModal').modal('show');
                this.form.fill(user);
            }
            ,

            deleteBuilding(id, name) {
                swal({
                    title: 'Ву уверены?',
                    text: "После удаления будет невозможно восстановить данный элемент",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Удалить',
                    cancelButtonText: 'Отмена'
                }).then((result) => {

                        if (result.value) {
                            this.form.delete('/api/user/' + id).then(() => {
                                swal(
                                    'Пользователь ' + name + ' был удален'
                                )
                                Fire.$emit('UpdateUserTable');

                            }).catch(() => {
                                swal(
                                    'Пользователь ' + name + 'не был удален', 'warning'
                                )

                            })
                        }
                    }
                )
            }
            ,
            loadUsers() {
                axios.get('/api/user').then(({data}) => (this.users = data, console.log(data.length)));
            },

            loadGroups() {
                axios.get('/listgroup?q=children')
                    .then(
                        ({data}) => (this.groups = data)
                    )
            },
        },

        created() {
            this.loadGroups();
            this.loadUsers();
            Fire.$on('UpdateUserTable', () => {
                this.loadUsers();
            });
        }
    }
</script>

<style scoped>

</style>
