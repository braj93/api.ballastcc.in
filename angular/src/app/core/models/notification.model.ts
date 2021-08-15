export interface Notification {
  created_at: any;
  notification_guid: any;
  notification_id: any;
  notification_template: any;
  notification_type_id: any;
  notification_type_key: any;
  params: any;
  receiver_user_id: any;
  refrence_id: any;
  sender_user_guid: any;
  sender_user_id: any;
  status: any;
  updated_at: any;
  p1: Array<P1>;
  p2: Array<P2>;
  p3: Array<P3>;
}

export interface P1 {
  name: string;
  guid: string;
}

export interface P2 {
  name: string;
  guid: string;
}

export interface P3 {
  name: string;
  guid: string;
}