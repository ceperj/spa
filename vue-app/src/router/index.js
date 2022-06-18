import { createRouter, createWebHistory } from 'vue-router';
import AddPerson from '../views/AddPerson.vue';
import AddProject from '../views/AddProject.vue';
import ChangeParameters from '../views/ChangeParameters.vue';
import GeneratePayroll from '../views/GeneratePayroll.vue';
import HomeView from '../views/HomeView.vue';
import NotFound from '../views/NotFound.vue';
import Reports from '../views/Reports.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'index',
      component: HomeView,
    },
    {
      path: '/cadastrar-projeto',
      name: 'addProject',
      // component: () => import('../views/AddProject.vue'),
      component: AddProject,
    },
    {
      path: '/cadastrar-pessoa-fisica',
      name: 'addPerson',
      // component: () => import('../views/AddPerson.vue'),
      component: AddPerson,
    },
    {
      path: '/relatorios/gfip',
      name: 'generateGfip',
      component: () => import('../views/Reports/Gfip.vue'),
    },
    {
      path: '/relatorios',
      name: 'reports',
      // component: () => import('../views/Reports.vue'),
      component: Reports,
    },
    {
      path: '/gerar-folha',
      name: 'generatePayroll',
      // component: () => import('../views/GeneratePayroll.vue'),
      component: GeneratePayroll,
    },
    {
      path: '/alterar-parametros/:submenu*',
      name: 'changeParameters',
      // component: () => import('../views/ChangeParameters.vue'),
      component: ChangeParameters,
    },
    {
      path: '/cadastrar-usuario',
      name: 'addUser',
      component: () => import('../views/User/Add.vue'),
    },
    {
      path: '/editar-usuario',
      name: 'listUser',
      component: () => import('../views/User/List.vue'),
    },
    {
      path: '/editar-usuario/:id',
      name: 'editUser',
      component: () => import('../views/User/Edit.vue'),
    },
    {
      path: '/cadastrar-cargo',
      name: 'addJob',
      component: () => import('../views/Job/Add.vue'),
    },
    {
      path: '/editar-cargo',
      name: 'listJob',
      component: () => import('../views/Job/List.vue'),
    },
    {
      path: '/editar-cargo/:id',
      name: 'editJob',
      component: () => import('../views/Job/Edit.vue'),
    },
    {
      path: '/editar-baterias',
      name: 'editBattery',
      component: () => import('../views/Battery/EditAll.vue'),
    },
    {
      path: '/alterar-aliquota-inss',
      name: 'changeInss',
      component: () => import('../views/Inss/Edit.vue'),
    },
    {
      path: '/editar-projeto',
      name: 'listProject',
      component: () => import('../views/Project/List.vue'),
    },
    {
      path: '/editar-projeto/:id',
      name: 'editProject',
      component: () => import('../views/Project/Edit.vue'),
    },
    {
      path: '/editar-pessoa-fisica',
      name: 'listPerson',
      component: () => import('../views/Person/List.vue'),
    },
    {
      path: '/editar-pessoa-fisica/:id',
      name: 'editPerson',
      component: () => import('../views/Person/Edit.vue'),
    },
    {
      path: '/listar-irpf',
      name: 'listIrpf',
      component: () => import('../views/Irpf/List.vue'),
    },
    {
      path: '/editar-irpf/',
      name: 'editIrpf',
      component: () => import('../views/Irpf/Edit.vue'),
    },
    {
      path: '/:path(.*)*',
      name: 'notfound',
      component: NotFound,
    },
  ],
});

export default router;
