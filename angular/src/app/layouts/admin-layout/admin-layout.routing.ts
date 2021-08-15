import { Routes } from '@angular/router';
import { DashboardComponent, AdminDashboardResolver } from '../../dashboard';
import { UserProfileComponent , UserEditProfileComponent} from '../../user-profile';
import { KnowledgebaseComponent, KnowledgebaseAddComponent, KnowledgebaseEditComponent} from '../../admin-component/knowledgebase';
import { TableListComponent } from '../../table-list/table-list.component';
import { TypographyComponent } from '../../typography/typography.component';
import { IconsComponent } from '../../icons/icons.component';
import { NotificationsComponent } from '../../notifications/notifications.component';
import { UpgradeComponent } from '../../upgrade/upgrade.component';
import { BroadcastComponent, AddBroadcastComponent, EditBroadcastComponent, BroadcastDetailByIdResolver } from '../../broadcast';
import { CrmComponent } from '../../admin-component/crm/crm.component';
import { CategoryComponent } from '../../admin-component/category/category.component';
import { AddCategoryComponent } from '../../admin-component/category/add-category/add-category.component';
import { EditCategoryComponent } from '../../admin-component/category/edit-category/edit-category.component';
import { CategoryDetailByIdResolver } from '../../admin-component/category/category-resolver.service';
import { UsersResolver } from '../../user-profile/users-resolver.service';
import { KnowledgebaseResolver } from '../../admin-component/knowledgebase/knowlegebase-resolver.service';
import { KnowledgebaseDetailByIdResolver } from '../../admin-component/knowledgebase/knowlegebase-detail-by-id-resolver.service';
import { AuthGuard } from '../../core';
import { AgencyBillingComponent } from '../../admin-component/agency-billing/agency-billing.component'
import { PricingPlansComponent, AddPricingPlanComponent, PricingPlanDetailByIdResolver, EditPricingPlanComponent} from '../../admin-component/pricing-plans';
export const AdminLayoutRoutes: Routes = [
    // {
    //     path: '',
    //     redirectTo: 'dashboard'
    // },
    {
      path: 'dashboard',
      component: DashboardComponent,
      canActivate: [AuthGuard],
      resolve: {
        dashboard_details: AdminDashboardResolver
     },
    },
    {
      path: 'user-profile',
      component: UserProfileComponent,
      resolve: {
        users: UsersResolver
      },
      canActivate: [AuthGuard]
    },
    {
        path: 'user-edit-profile/:id',
        component: UserEditProfileComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'icons',
        component: IconsComponent,
        canActivate: [AuthGuard]
     },
     {
        path: 'notifications',
        component: NotificationsComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'typography',
        component: TypographyComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'upgrade',
        component: UpgradeComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'tablelist',
        component: TableListComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'broadcast',
        component: BroadcastComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'broadcast/add',
        component: AddBroadcastComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'broadcast/edit/:id',
        component: EditBroadcastComponent,
        resolve: {
            broadcast: BroadcastDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'crm',
        component: CrmComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'agency-billing',
        component: AgencyBillingComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'category',
        component: CategoryComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'add-category',
        component: AddCategoryComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'edit-category/:id',
        component: EditCategoryComponent,
        resolve: {
           get_detail_by_id: CategoryDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'knowledgebase',
        component: KnowledgebaseComponent,
        resolve: {
            kbdata: KnowledgebaseResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'knowledgebase-add',
        component: KnowledgebaseAddComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'knowledgebase-edit/:id',
        component: KnowledgebaseEditComponent,
        resolve: {
           get_by_id: KnowledgebaseDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'pricing-plans',
        component: PricingPlansComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'add-pricing-plan',
        component: AddPricingPlanComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'pricing-plan/:id/edit',
        component: EditPricingPlanComponent,
        resolve: {
           details: PricingPlanDetailByIdResolver
        },
        canActivate: [AuthGuard]
    }
    // }, {
    //     path: 'maps',
    //     component: MapsComponent
    // { path: 'dashboard',      component: DashboardComponent },
    // { path: 'user-profile',   component: UserProfileComponent },
    // { path: 'table-list',     component: TableListComponent },
    // { path: 'typography',     component: TypographyComponent },
    // { path: 'icons',          component: IconsComponent },
    // { path: 'maps',           component: MapsComponent },
    // { path: 'notifications',  component: NotificationsComponent },
    // { path: 'upgrade',        component: UpgradeComponent },
    // { path: 'login',        component: LoginComponent },
];
