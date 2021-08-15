import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, BehaviorSubject, ReplaySubject } from 'rxjs';

import { ApiService } from './api.service';
import { JwtService } from './jwt.service';
import { User } from '../models';
import { map, distinctUntilChanged } from 'rxjs/operators';
import { Title, Meta } from '@angular/platform-browser';


@Injectable()
export class UserService {
  public userData: any = {};
  public alerts: any = [];
  public title = 'Tikisites';
  public is_show_login: any = false;
  private currentUserSubject = new BehaviorSubject<User>({} as User);
  public currentUser = this.currentUserSubject.asObservable().pipe(distinctUntilChanged());

  public popupRef: any = [];
  private isAuthenticatedSubject = new ReplaySubject<boolean>(1);
  public isAuthenticated = this.isAuthenticatedSubject.asObservable();
  public search_keyword: any;
  public cart: any = [];
  public thankyoudata: any;
  public counts: any = {};
  public param_id: any;
  public page_title: any = 'Dashoboard';
  public my_title: any = '';
  constructor(
    private titleService: Title,
    private meta: Meta,
    private apiService: ApiService,
    private http: HttpClient,
    private jwtService: JwtService,
  ) { }


  setPersonal(data: any) {
    this.userData.name = data.name;
    this.userData.email = data.email;
    this.userData.phone = data.phone;
    this.userData.business_name = data.business_name;
    this.userData.user_sub_type = data.user_sub_type;
    this.userData.password = data.password;
    this.userData.pricing_plan_id = data.pricing_plan_id;
    this.userData.amount = data.amount;
    this.userData.plan_name = data.plan_name;

    // Validate Personal Step in Workflow
    // this.workflowService.validateStep(STEPS.personal);
  }

  public check_unique_email_phone(email, phone): Observable<any> {
    return this.apiService.post('/users/email_and_phone_validate', { 'email': email, 'phone': phone })
      .pipe(map(
        data => {
          return data.data;
        }
      ));
  }

  getCommaSepratedString(arr) {
    if (arr) {
      const arr_2 = [];
      if (arr.length !== undefined || arr.length != null || arr.length !== '') {
        for (const obj of arr) {
          arr_2.push(obj.category_name);
        }
      }
      const str = arr_2.join(', ');
      return str;
    }
    return '--';
  }

  getCommaSepratedArrayString(arr) {
    if (arr) {
      const arr_2 = [];
      if (arr.length !== undefined || arr.length != null || arr.length !== '') {
        for (const obj of arr) {
          arr_2.push(obj);
        }
      }
      const str = arr_2.join(', ');
      return str;
    }
    return '--';
  }

  setTitle(newTitle: string) {
    this.titleService.setTitle(newTitle);
  }

  updateDescription(data: string) {
    this.meta.updateTag({ name: 'description', content: data });
  }
  updateContent(data: string) {
    this.meta.updateTag({ name: 'content', content: data });
  }

  // Verify JWT in localstorage with server & load user's info.
  // This runs once on application startup.
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

  getOrderlist(input): Observable<any> {
    return this.apiService.post('/carts/get_cart', input)
      .pipe(map((data) => data));
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

  attemptAuthLogin(credentials: any): Observable<User> {
    return this.apiService.post('/users/login', credentials)
      .pipe(map(
        data => {
          this.setAuth(data.data);
          return data;
        }
      ));
  }

  submitTeamMemberSignupForm(credentials: any): Observable<User> {
    return this.apiService.post('/users/register_team_member', credentials)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  attemptAuthRegister(credentials: any): Observable<User> {
    return this.apiService.post('/users/register', credentials)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  paymentAndRegister(credentials: any): Observable<User> {
    return this.apiService.post('/users/register_members', credentials)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  socialLogin(credentials: any): Observable<User> {
    let order_id = localStorage.getItem('order_id');
    if (order_id != null || order_id !== undefined || order_id !== '') {
      order_id = order_id;
    } else {
      order_id = '';
    }
    const reqbody = {
      'order_id': order_id,
      'social_user_id': credentials.id,
      'social_email': credentials.email,
      'social_token': credentials.provider === 'google' ? credentials.idToken : credentials.token,
      'social_login_type': credentials.provider
    }
    return this.apiService.post('/users/social_login/', reqbody)
      .pipe(map(
        data => {
          this.setAuth(data.data);
          return data;
        }
      ));
  }



  getCurrentUser(): User {
    return this.currentUserSubject.value;
  }

  // Update the user on the server (email, pass, etc)
  update(user: any): Observable<User> {
    return this.apiService
      .put('/user', { user })
      .pipe(map(data => {
        // Update the currentUser observable
        this.currentUserSubject.next(data.user);
        return data.user;
      }));
  }

  updateUser(user: any): Observable<User> {
    return this.apiService
      .post('/users/user_update', user)
      .pipe(map(data => {
        // Update the currentUser observable
        // this.currentUserSubject.next(data.user);
        this.populate();
        return data;
      }));
  }

  verifyEmail(token: any): Observable<User> {
    return this.apiService.post('/users/verify_account/', { 'verify_code': token })
      .pipe(map(
        data => {
          this.setAuth(data.data);
          return data.data;
        }
      ));
  }

  requestOriginal(formData: any): Observable<User> {
    return this.apiService.post('/requestoriginal/send/', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  forgotPassword(formData: any): Observable<User> {
    return this.apiService.post('/users/forgot_password', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  checkPricingPlanStatus(formData: any): Observable<User> {
    return this.apiService.post('/users/check_pricing_plan_status', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getCountries(): Observable<User> {
    return this.apiService.get('/users/get_countries')
      .pipe(map(
        data => {
          return data.data;
        }
      ));
  }

  changePassword(formData: any): Observable<User> {
    return this.apiService.post('/users/change_password/', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  setPassword(formData: any): Observable<User> {
    return this.apiService.post('/users/set_password/', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  resetPassword(formData: any): Observable<User> {
    return this.apiService.post('/users/reset_password/', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  contactUs(formData: any): Observable<any> {
    return this.apiService.post('/users/contact/', formData)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  connectToStripe(): Observable<any> {
    return this.apiService.get('/stripe/connect_with_stripe/')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  revokeStripeAccount(): Observable<any> {
    return this.apiService.get('/users/revoke_stripe_account')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  userProfileRefresh() {
    console.log('userProfileRefresh');
    this.apiService.get('/users/profile')
      .subscribe(data => this.currentUserSubject.next(data.data));
  }

  getCleanedString(str) {
    if (str) {
      str = str.replace(/_/g, ' ');
      return str;
    }
    return null
  }

  getArrayToString(arr) {
    // if (str) {
    const str = arr.join(' ,');
    return str;
    // }
    // return null
  }

  stringReplace(str, search_value, new_value) {
    return str ? str.replace(search_value, new_value) : null;
  }

  phoneNoMask(rawNum) {
    rawNum = rawNum.charAt(0) !== 0 ? rawNum : '' + rawNum;

    let newStr = '';
    let i = 0;

    for (; i < Math.floor(rawNum.length / 3) - 1; i++) {
      newStr = newStr + rawNum.substr(i * 3, 3) + '-';
    }

    return newStr + rawNum.substr(i * 3);
  }

  getIndexOfList(list, field, val) {
    const index = list.map(function (obj, idx) {
      if (obj[field] === val) {
        return idx;
      }
    }).filter(isFinite);

    return index;
  }

  // GET USER'S LIST
  usersList(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/users_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET INVITED USER'S LIST
  invitedUsersList(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/invited_users_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET TEAM MEMBER'S LIST
  getTeamMembersList(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/team_members_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // INVITE USERS
  inviteUsers(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/invite_users', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // INVITE MEMBER
  inviteMember(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/invite_member', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // CHANGE USER STATUS
  changeUserStatus(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/change_status', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  sendEmailAgainTomember(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/send_email_again_to_member', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET STATES LIST
  getStatesList(): Observable<User> {
    return this.apiService.get('/admin/site_manage/states')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET DETAIL BY ID
  getDetailByUserId(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/user_detail_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // UPDATE USER DETAIL
  updateUserDetail(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/update_user', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET DETAIL BY ID
  getEmailById(input: any): Observable<User> {
    return this.apiService.post('/users/get_email_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET USERS LIST
  getUserList(input: any): Observable<User> {
    return this.apiService.post('/admin/users_manage/get_user_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getSubscriptionDetails(input: any): Observable<User> {
    return this.apiService.post('/users/get_subscription_details', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getInvoces(input: any): Observable<User> {
    return this.apiService.post('/users/get_invoce_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  updatePlan(input: any): Observable<User> {
    return this.apiService.post('/users/update_plan', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  sendHelpEmail(input: any): Observable<User> {
    return this.apiService.post('/users/send_help_email', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
}
