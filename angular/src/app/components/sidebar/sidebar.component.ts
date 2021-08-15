import { Component, OnInit } from '@angular/core';
import { UserService, AdminService } from '../../core';
import { Router } from '@angular/router';
declare const $: any;
declare interface RouteInfo {
    path: string;
    title: string;
    icon: string;
    class: string;
}
export const ROUTES: RouteInfo[] = [
    { path: '/console/dashboard', title: 'Dashboard',  icon: 'dashboard', class: '' },
    { path: '/console/user-profile', title: 'User Management',  icon:'user', class: '' },
    { path: '/console/broadcast', title: 'Broadcast',  icon:'broadcast', class: '' },
    { path: '/console/crm', title: 'CRM',  icon:'lock', class: '' },
    // { path: '/console/tablelist', title: 'Broadcast',  icon:'broadcast', class: '' },
    // { path: '/console/typography', title: 'Knowledge base',  icon:'idea', class: '' },
    // { path: '/login', title: 'Campaigns',  icon:'seo', class: '' },
    // { path: '/console/icons', title: 'CRM',  icon:'system', class: '' },
    // { path: '/users/login', title: 'Logout',  icon:'logout', class: '' },
    // { path: '/console/notifications', title: 'Notifications',  icon:'notifications', class: '' },
    // { path: '/console/upgrade', title: 'Upgrade to PRO',  icon:'unarchive', class: 'active-pro' },
];

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.css']
})
export class SidebarComponent implements OnInit {
  menuItems: any[];
  categories_list: any[];
  currentUser: any;
  public isCollapsed?: boolean = false;
  constructor(
    public userService: UserService,
    public adminService: AdminService,
    public router: Router
  ) { }

  ngOnInit() {
    this.userService.currentUser.subscribe(
      (userData) => {
        this.currentUser = userData;
      }
    );
    this.menuItems = ROUTES.filter(menuItem => menuItem);
    this.adminService.categoriesList().subscribe((response: any) => {
      this.categories_list = response.data;
      this.categories_list.forEach(function (value) {
        value.url = '/console/category?category_id=' + value.id;
    });
    }, err => {
      this.userService.alerts.push({
        type: 'danger',
        msg: err.message,
        timeout: 4000
      });
    });
  }
  isMobileMenu() {
      if ($(window).width() > 991) {
          return false;
      }
      return true;
  };

  logout() {
    this.userService.purgeAuth();
    this.router.navigateByUrl('/users');
  }

  expandAndRedirect() {
    this.isCollapsed = !this.isCollapsed;
    // this.router.navigateByUrl('/user/knowledgebase');
  }

  myFunc(event){
    event.preventDefault();
    event.stopPropagation();
    this.isCollapsed = !this.isCollapsed;
    var element = event.target;
        while (
          element.getAttribute("data-toggle") != "collapse" &&
          element != document.getElementsByTagName("html")[0]
          ) {
          element = element.parentNode;
      }
      element = element.parentNode.children[1];

      if (
        element.classList.contains("collapse") &&
        !element.classList.contains("show")
        ) {
        element.classList = "before-collapsing";
      var style = element.scrollHeight;

      element.classList = "collapsing";
      setTimeout(function() {
        element.setAttribute("style", "height:" + style + "px");
      }, 1);
      setTimeout(function() {
        element.classList = "collapse show";
        element.removeAttribute("style");
        this.isCollapsing = undefined;
      }, 350);
    } else {
      var style = element.scrollHeight;
      setTimeout(function() {
        element.setAttribute("style", "height:" + (style + 20) + "px");
      }, 3);
      setTimeout(function() {
        element.classList = "collapsing";
      }, 3);
      setTimeout(function() {
        element.removeAttribute("style");
      }, 20);
      setTimeout(function() {
        element.classList = "collapse";
        this.isCollapsing = undefined;
      }, 400);
    }
  }
}
