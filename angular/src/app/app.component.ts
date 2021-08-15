import { Component, OnInit } from '@angular/core';
import { UserService } from './core';
import { ActivatedRoute, Router } from '@angular/router';
import { Location } from '@angular/common';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {

  currentUser: any;
  constructor(
    private router: Router,
    private route: ActivatedRoute,
    private userService: UserService,
    private location: Location
  ) { }
  ngOnInit() {
    this.userService.populate();
    this.userService.isAuthenticated.subscribe(
      (isAuthenticated) => {
        if (isAuthenticated) {
          this.userService.currentUser.subscribe(
            (userData) => {
              this.currentUser = userData;
              if (this.currentUser.user_type === 'OWNER' || this.currentUser.user_type === 'ADMIN') {
                this.router.navigateByUrl('/console/dashboard');
              } else {
                this.router.navigateByUrl('/user/user-dashboard');
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

