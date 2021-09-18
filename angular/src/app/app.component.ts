import { Component, OnInit } from '@angular/core';
import { AdminService } from './core';
import { ActivatedRoute, Router } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  currentUser: any;
  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private adminService: AdminService,
    private location: Location
  ) { }
  ngOnInit() {
    this.adminService.populate();
    this.adminService.isAuthenticated.subscribe(
      (isAuthenticated) => {
        // console.log(isAuthenticated);
        if (isAuthenticated) {
          this.adminService.currentUser.subscribe(
            (userData) => {
              this.currentUser = userData;
              if (this.currentUser.user_type === 'OWNER' || this.currentUser.user_type === 'ADMIN') {
                this.router.navigateByUrl('/console/dashboard');
              } else {
                // this.router.navigateByUrl('/admin/dashboard');
              }
            }
          );

        } else {
          if (this.location.path().indexOf('users/payment') > -1) {
            this.router.navigateByUrl('/users/login');
          }
        }
      });
  }
}
