//--
//-- app.routing.ts
//--
//-- The route provider transalates URL paths to Angular components.  This route provider is a stripped 
//-- down version of the FUSE template.  It is overly complication and will bite you if you get
//-- creative with it.
//--
//-- Just carefully add your paths and exist safely.  Follow the structure of the Resolvers for MIST.
//-- They are critical due to the slow performance of the backend servers.
//--
//-- ESH 2/4/22.
//--
import { Route } from '@angular/router';;
import { LayoutComponent } from 'app/layout/layout.component';
import { InitialDataResolver } from 'app/app.resolvers';
import { AuthGuardOriginal } from './auth.guard';
import { DataResolver, MenuResolver, UserResolver } from './data.resolver';
//--
//-- These import statements will be created automatically when you create the path in the 
//-- routes section if you allow predictive typing to do it's thing when you select the 
//-- component.
//--
import { AssetDashboardComponent } from './assets/asset-dashboard/asset-dashboard.component';
import { ActiveAssessmentsComponent } from './assets/active-assessments/active-assessments.component';
import { AssetSearchComponent } from './assets/asset-search/asset-search.component';
import { FacilityAssetDashboardComponent } from './assets/facility-asset-dashboard/facility-asset-dashboard.component';
import { FacilitySearchComponent } from './assets/facility-search/facility-search.component';
import { MistLogoutComponent } from './assets/mist-logout/mist-logout.component';
import { MistLogoutHomeComponent } from './assets/mist-logout-home/mist-logout-home.component';
import { OverdueTestComponent } from './assets/overdue-test/overdue-test.component';

// @formatter:off
/* eslint-disable max-len */
/* eslint-disable @typescript-eslint/explicit-function-return-type */
export const appRoutes: Route[] = [
    {path: '', pathMatch : 'full', redirectTo: 'sign-in'},
    {path: 'signed-in-redirect', pathMatch : 'full', redirectTo: 'sign-in'},
    {
        path: '',
        canActivate: [AuthGuardOriginal],
        component: LayoutComponent,
        data: {
            layout: 'empty'
        },
        //--
        //-- Add paths for MIST here.
        //--    Resolvers:
        //--         MenuResolver - Gets the left vertical menu.
        //--         DataResolver - Gets the data presented on the screen.
        //--             Add an entry to the switch statemment in "data/index.php" on the server to return data based on the path.  
        //--         UserResolver - Gets user account information.
        //--
        children: [
            {path: 'sadmin', component: AssetDashboardComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'active-assessments', component: ActiveAssessmentsComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'asset-search', component: AssetSearchComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'facility-asset-dashboard', component: FacilityAssetDashboardComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'facility-search', component: FacilitySearchComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'mist-logout', component: MistLogoutComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'mist-home', component: MistLogoutHomeComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, },
            {path: 'overdue-tests', component: OverdueTestComponent, resolve: { menudata: MenuResolver, data: DataResolver, userdata: UserResolver }, }
        ]
    },
    //--
    //-- Legacy Path from the FUSE template.  We don't use it but if it is removed, the application will stop working.
    //-- I don't know why.  (ESH: 2/4/22)
    //--
    {
        path       : '',
        component  : LayoutComponent,
        resolve    : {
            initialData: InitialDataResolver,
        },
        children   : [
            {path: 'ui', children: [
                {path: 'forms', children: [
                    {path: 'fields', loadChildren: () => import('app/modules/admin/ui/forms/fields/fields.module').then(m => m.FormsFieldsModule)}
                ]},
             ]},
            {path: '404-not-found', pathMatch: 'full', loadChildren: () => import('app/modules/admin/pages/error/error-404/error-404.module').then(m => m.Error404Module)},
            {path: '**', redirectTo: '404-not-found'}
        ]
    }
];
