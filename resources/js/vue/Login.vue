<template>
    <div>
        <form autocomplete="off" @submit.prevent="login()" method="post">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" placeholder="user@example.com"
                       v-model="form.email"
                       :class="{invalid: ($v.form.email.$dirty && !$v.form.email.required) || ($v.form.email.$dirty && !$v.form.email.email)}">
                <small
                    class="helper-text invalid"
                    v-if="$v.form.email.$dirty && !$v.form.email.required"
                >Поле Email не должно быть пустым</small>
                <small
                    class="helper-text invalid"
                    v-else-if="$v.form.email.$dirty && !$v.form.email.email"
                >Введите корретный Email</small>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" class="form-control"
                       v-model="form.password"
                       :class="{invalid: ($v.form.password.$dirty && !$v.form.password.required) || ($v.form.password.$dirty && !$v.form.password.email)}">
                <small
                    class="helper-text invalid"
                    v-if="$v.form.password.$dirty && !$v.form.password.required"
                >Введите пароль</small>
                <small
                    class="helper-text invalid"
                    v-else-if="$v.form.password.$dirty && !$v.form.password.minLength"
                >Пароль должен быть {{$v.form.password.$params.minLength.min}} символов. Сейчас он {{form.password.length}}</small>
            </div>

            <button type="submit" class="btn btn-default">Sign in</button>
        </form>
    </div>
</template>
<script>
import {email, required, minLength} from 'vuelidate/lib/validators'
export default {
    name: "login",  // using EXACTLY this name is essential

    data:function(){
        return{
            form:{
                email:'',
                password:'',
            },
            errors:[],
        }
    },
    validations: {
        form:{
            email: {email, required},
            password: {required, minLength: minLength(6)}
        }

    },
    methods:{
        login(){
            if (this.$v.$invalid) {
                this.$v.$touch()
                return
            }
            axios.post('api/auth/login', this.form)
                .then(prevent =>{
                    //this.getMyHeaderLinks();
                    //this.$pusher.push('/cabinet')
                    console.log(123)
                })
                .catch(error =>{

                })
        }
    }
}
</script>
