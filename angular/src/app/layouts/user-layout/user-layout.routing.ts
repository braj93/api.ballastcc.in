import { Routes } from '@angular/router';
import { CampaignComponent, AddCampaignComponent, EditCampaignComponent, CampaignDetailByIdResolver, CampaignSettingComponent, CampaignStepTwoComponent, ViewCampaignComponent, ViewCampaignDetailByIdResolver, CampaignCallTrackingComponent, CampaignCallTrackingResolver, CampaignReportDetailResolver } from '../../campaign';
import { LandingpageComponent } from '../../landingpage';
import { TemplateComponent, TemplateDetailResolver } from '../../template';
import { AuthGuard } from '../../core';
import {
    UserPlanUpdateComponent,
    UserDashboardResolver,
    UserDashboardComponent,
    MyBusinessComponent,
    UserCrmComponent,
    UserBillingComponent,
    UserBillingResolver,
    UserCrmResolver,
    UserKnowledgebaseComponent,
    UserKnowledgebaseResolver,
    UserKnowledgebaseDetailComponent,
    UserKnowledgebaseDetailResolver,
    ViewProileComponent,
    ProfileSettingsComponent,
    ChangePasswordComponent,
    NoteAddComponent, ContactDetailResolver,
    UserBroadcastComponent,
    ViewBroadcastComponent,
    ViewBroadcastDetailResolver,
    MembersComponent,
    UserDetailsResolver,
    UserHelpComponent
} from '../../user-component';
export const UserLayoutRoutes: Routes = [
    // {
    //     path: '',
    //     redirectTo: 'user-dashboard',
    // },
    {
        path: 'user-dashboard',
        component: UserDashboardComponent,
        canActivate: [AuthGuard],
        resolve: {
            dashboard_details: UserDashboardResolver
        }
    },
    {
        path: 'my-business/:id',
        component: MyBusinessComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'user-crm/:id',
        component: UserCrmComponent,
        resolve: {
            crm_contact: UserCrmResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'billing/:id',
        component: UserBillingComponent,
        resolve: {
            subscription_details: UserBillingResolver,
            userDetails: UserDetailsResolver,
        },
        canActivate: [AuthGuard]
    },
    {
        path: ':id/plan',
        component: UserPlanUpdateComponent,
        resolve: {
            subscription_details: UserBillingResolver,
            userDetails: UserDetailsResolver,
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'knowledgebase',
        component: UserKnowledgebaseComponent,
        resolve: {
            user_knowledgebase: UserKnowledgebaseResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'knowledgebase/:id',
        component: UserKnowledgebaseDetailComponent,
        resolve: {
            user_knowledgebase_detail: UserKnowledgebaseDetailResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'view-profile',
        component: ViewProileComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'profile-settings',
        component: ProfileSettingsComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'change-password',
        component: ChangePasswordComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'note/add/:id',
        component: NoteAddComponent,
        resolve: {
            contact_detail: ContactDetailResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'broadcast',
        component: UserBroadcastComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'view-broadcast/:id',
        component: ViewBroadcastComponent,
        resolve: {
            view_broadcast_detail: ViewBroadcastDetailResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns',
        component: CampaignComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/create',
        component: AddCampaignComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/:id/edit',
        component: EditCampaignComponent,
        resolve: {
            detail: CampaignDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/:id/templates',
        component: LandingpageComponent,
        resolve: {
            detail: CampaignDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/:id/design',
        component: TemplateComponent,
        resolve: {
            detail: TemplateDetailResolver
        }
    },
    {
        path: 'campaigns/:id/setting',
        component: CampaignSettingComponent,
        resolve: {
            detail: CampaignDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/:id/qr-code',
        component: CampaignStepTwoComponent,
        resolve: {
            detail: CampaignDetailByIdResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/:id/call-tracking-number',
        component: CampaignCallTrackingComponent,
        resolve: {
            detail: CampaignDetailByIdResolver,
            tracker_detail: CampaignCallTrackingResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'campaigns/:id/view',
        component: ViewCampaignComponent,
        resolve: {
            detail: ViewCampaignDetailByIdResolver,
            // report_detail: CampaignReportDetailResolver
        },
        canActivate: [AuthGuard]
    },
    {
        path: 'members/:id',
        component: MembersComponent,
        canActivate: [AuthGuard]
    },
    {
        path: 'help',
        component: UserHelpComponent,
        canActivate: [AuthGuard]
    },
];
