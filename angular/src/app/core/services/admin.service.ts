import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject, ReplaySubject } from 'rxjs';
import { User } from '../models';
import { ApiService } from './api.service';
import { JwtService } from './jwt.service';
import { map, distinctUntilChanged } from 'rxjs/operators';
import { Title, Meta } from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  public userData: any = {};
  public alerts: any = [];
  private currentUserSubject = new BehaviorSubject<User>({} as User);
  public currentUser = this.currentUserSubject.asObservable().pipe(distinctUntilChanged());
  private isAuthenticatedSubject = new ReplaySubject<boolean>(1);
  public isAuthenticated = this.isAuthenticatedSubject.asObservable();
  public page_title: any = 'Dashoboard';
  constructor(
    private apiService: ApiService,
    private http: HttpClient,
    private jwtService: JwtService,
  ) { }

  adminLogin(credentials: any): Observable<User> {
    return this.apiService.post('/admin/auth/login', credentials)
      .pipe(map(
        data => {
          this.setAuth(data.data);
          return data;
        }
      ));
  }
  setAuth(user: User) {
    // Save JWT sent from server in localstorage
    this.jwtService.saveToken(user.session_key);
    // Set current user data into observable
    this.currentUserSubject.next(user);
    // Set isAuthenticated to true
    this.isAuthenticatedSubject.next(true);
    localStorage.setItem('user', JSON.stringify(user));
  }
  purgeAuth() {
    // Remove JWT from localstorage
    this.jwtService.destroyToken();
    // Set current user to an empty object
    this.currentUserSubject.next({} as User);
    // Set auth status to false
    this.isAuthenticatedSubject.next(false);
    localStorage.removeItem('user');
  }
  populate() {
    // If JWT detected, attempt to get & store user's info
    if (this.jwtService.getToken()) {
      this.apiService.get('/users/profile')
        .subscribe(
          data => this.setAuth(data.data),
          err => this.purgeAuth()
        );
    } else {
      // Remove any potential remnants of previous auth states
      this.purgeAuth();
    }
  }
  getAdminDashboard(): Observable<User> {
    return this.apiService.get('/admin/master/get_admin_dashboard')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
  getClassList():Observable<User>{
    return this.apiService.get('/admin/master/get_classes')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
  getPaymentsList(input: any): Observable<User> {
    return this.apiService.post('/admin/payments/get_fee_list',input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
  getStudentsList(input: any): Observable<User> {
    return this.apiService.post('/admin/students/get_student_list',input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
  
  
}
