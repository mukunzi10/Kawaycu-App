<template>
  <div>
        <!-- Navbar -->
    <nav class="navbar">

    <div class="navbar-container">
        <div class="navbar-user">
         <label>{{userName}}</label>
         <span></span>
         <img :src="logo" class="logo userAvatar" draggable="false"/>
        </div>
      <div class="navbar-left">

          <button @click="toggle()" class="toggleButton">
            <i class="fa fa-align-justify"></i>
          </button>
      </div>
    </div>
    
    </nav>

    <div v-bind:class="[toggleSidebar ? 'sidebar sidebar-expand' :'sidebar']">
        <div class="title-holder">
          <img :src="logo" class="logo" draggable="false"/>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <label>KawaYacu</label>
        </div>
        <div class="sidebar-menu">
          <ul class="sideNav">
            <router-link to="/dashboard" active-class="active" class="routerLink"><li><i class="fa fa-align-justify"></i> <h5>Dashboard</h5></li></router-link>
            <router-link to="/sites"><li><i class="fa fa-align-justify"></i> <h5>Sites</h5></li></router-link>
            <router-link to="/agents"><li><i class="fa fa-book"></i> <h5>Agents</h5></li></router-link>
            <router-link to="/farmers"><li><i class="fa fa-users"></i> <h5>Famers</h5></li></router-link>
            <router-link to="/harvest"><li><i class="fa fa-users"></i> <h5>Harvest</h5></li></router-link>
            <router-link to="/payments"><li><i class="fa fa-users"></i> <h5>Payments</h5></li></router-link>
            <li @click="logout()"><i class="fa fa-sign-out" ></i> <h5>Logout</h5></li>
          </ul>
        </div>
    </div>

  </div>
<!-- Navbar -->
</template>

<style  lang="scss" scoped>
.navbar{
  width:calc(100% - 240px);
  height:70px;
  background-color: #508aeb !important;
  float: right;
  box-shadow:0px 0px 10px  0px rgba(0,0,0,0.19);
  display:flex;
  z-index:1;
}
.navbar-container{
  display:flex;
  width:100%;
  height:50px;
  justify-content:space-between;
}
.title-holder{
  display:flex;
  justify-content: space-around;
}
.navbar-left{
    display:flex;
   
}
.navbar-user{
  width:90%;
  display: flex;
  align-items:center;
  justify-content:flex-end;
}
.navbar-user label{
  margin-right:1rem;
  color:white;
}
.sidebar{
      width:240px;
      min-height:100%;
      background:white;
      z-index:1;
      transition:width 1s linear;
      overflow-x: hidden;
      position:fixed;
      top:0;
      color:black;
      box-shadow:0px 0px 5px 0px rgba(0,0,0,0.19);
      transform:translateX(-100%);
      padding:0px;
      padding-top:10px;
      transition:transform .1s ease-in;
  }
.logo{
  width:60px;
  height:60px;
  border-radius:50%;
}
.label{
  font-size:17px;
  margin-top:10px;
}
  .sidebar-expand{
    transform:translateX(0%);
    transition:transform .1s ease-in;
  }
  .sidebar .title-holder{
     width:100%;
     min-height:70px;
     height:auto;
     border-bottom:1px solid #f5f6f5;
     display:flex;
     justify-content: center;
     align-items: center;
     color:white;
     opacity:0.7;
     padding:5px;

    margin-top:-10px;
     background:dodgerblue;
     justify-content: center;
  }
  .sidebar .sidebar-menu{
    width:100%;
    display:flex;
    flex-direction:column;
    min-height:200px;
    justify-content: center;
    height:auto;
  }
  .sidebar .sidebar-menu ul{
    list-style-type: none;
    margin-top:1rem;
  }
  .sidebar .sidebar-menu ul li{
    margin-top:1rem;
    display:flex;
    align-items:center;
    cursor: pointer;
  }
  .sidebar .sidebar-menu ul li i{
    color:dodgerblue;
  }
  .sidebar .sidebar-menu ul li h5{
      font-size:16px;
      margin-top:4px;
      color:black;
      margin-left:10px;
      opacity:0.8;
    }
  .active{
    background:white;
    color:black
  }
  .routerLink{
    text-decoration: none;
    list-style: none;
  }

  .toggleButton{
    display:none;
  }
  .userAvatar{
    width:45px;
    height:45px;
  }

  @media (max-width:500px) {
    .navbar{
        width:100%;
      }
    .toggleButton{
    display:block;
  }
  }
</style>

<script>
export default {
  data(){
    return {
      toggleSidebar:true,
      studentType:false,
      adminType:false,
      logo:"storage/images/kawayacuLogo.jpg",
      userName:""
    }
  },
  methods:{
    toggle()
    {
       this.toggleSidebar=!this.toggleSidebar;
    },
    logout(){
      this.$store.commit('logout');
      console.log("logout");
    },
  },
  mounted(){
    let authenticated=this.$store.state.authenticated;
    this.userName=this.$store.state.user.name;
  }
  
}
</script>
