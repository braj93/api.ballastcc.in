import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
declare const $: any;
declare interface RouteInfo {
  path: string;
  title: string;
  icon: string;
  class: string;
}
export const ROUTES: RouteInfo[] = [
  { path: '/admin/dashboard', title: 'Dashboard',  icon: 'dashboard', class: '' },
  { path: '/admin/student', title: 'User Management',  icon:'user', class: '' },
  { path: '/admin/master', title: 'Broadcast',  icon:'broadcast', class: '' },
  { path: '/admin/teacher', title: 'CRM',  icon:'lock', class: '' },
  { path: '/admin/fee', title: 'CRM',  icon:'lock', class: '' },
];
@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit {

  public isCollapsed?: boolean = false;
  constructor(public router: Router) { }

  ngOnInit(): void {
  }
  isMobileMenu() {
    if ($(window).width() > 991) {
        return false;
    }
    return true;
};
logout() {
  // this.userService.purgeAuth();
  this.router.navigateByUrl('/users');
}
expandAndRedirect() {
  this.isCollapsed = !this.isCollapsed;
  // this.router.navigateByUrl('/user/knowledgebase');
}

}
