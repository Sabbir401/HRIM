<script>
import axios from "axios";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";

export default {
  setup() {
    const router = useRouter();
    let form = reactive({
      name: "",
      email: "",
      password: "",
      c_password: "",
    });

    let errors = ref([]);

    const register = async () => {
      await axios
        .post("/api/register", form)
        .then((res) => {
          if (res.data.success) {
            // localStorage.setItem("token", res.data.data.token);
            router.push({ name: "Dashboard" });
          }
        })
        .catch((e) => {
          errors.value = e.response.data.message;
        });
    };
    return {
      form,
      register,
      errors,
    };
  },
};
</script>


<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-6 mt-5">
        <h2>Register</h2>
        <p class="text-danger" v-for="error in errors" :key="error">
          <span v-for="err in error" :key="err">{{ err }}</span>
        </p>
        <form @submit.prevent="register">
          <div class="from-group">
            <label for="name">Name</label>
            <input
              type="text"
              class="form-control"
              id="name"
              v-model="form.name"
            />
          </div>
          <div class="from-group">
            <label for="email">Email Address</label>
            <input
              type="email"
              class="form-control"
              id="email"
              v-model="form.email"
            />
          </div>
          <div class="from-group">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control mb-2"
              id="password"
              v-model="form.password"
            />
          </div>
          <div class="from-group">
            <label for="c_password">Confirm Password</label>
            <input
              type="password"
              class="form-control mb-2"
              id="c_password"
              v-model="form.c_password"
            />
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
      </div>
    </div>
  </div>

  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="row w-100 m-0">
        <div
          class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg"
        >
          <div class="card col-lg-4 mx-auto">
            <div class="card-body px-5 py-5">
              <h3 class="card-title text-left mb-3">Register</h3>
              <form @submit.prevent="register">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control p_input" v-model="form.name" />
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control p_input" v-model="form.email" />
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control p_input" v-model="form.password" />
                </div>
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" class="form-control p_input" v-model="form.c_password" />
                </div>

                <a href="#" class="forgot-pass float-left">Forgot password</a>

                <div class="text-center">
                  <button
                    type="submit"
                    class="btn btn-primary btn-block enter-btn"
                  >
                    Login
                  </button>
                </div>
                <div class="d-flex">
                  <button class="btn btn-facebook col mr-2">
                    <i class="mdi mdi-facebook"></i> Facebook
                  </button>
                  <button class="btn btn-google col">
                    <i class="mdi mdi-google-plus"></i> Google plus
                  </button>
                </div>
                <p class="sign-up text-center">
                  Already have an Account?<a href="#"> Sign Up</a>
                </p>
                <p class="terms">
                  By creating an account you are accepting our<a href="#">
                    Terms & Conditions</a
                  >
                </p>
              </form>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- row ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
</template>

