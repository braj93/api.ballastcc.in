// The file contents for the current environment will overwrite these during build.
// The build system defaults to the dev environment which uses `environment.ts`, but if you do
// `ng build --env=prod` then `environment.prod.ts` will be used instead.
// The list of which env maps to which file can be found in `.angular-cli.json`.
// Production
// export const environment = {
//   production: false,
//   stripe_sk: 'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242',
//   stripe_pk: 'pk_test_DSSTxP5NaHXwInGOlRX2rYM300aoJSRuBB', // client
//   api_url: 'https://www.tikisites.com/api',
//   site_addr: 'https://www.tikisites.com',
//   site_address: 'https://www.tikisites.com/',
// };

// Staging
//  export const environment = {
//   production: false,
//    stripe_sk: 'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242',
//    stripe_pk: 'pk_test_DSSTxP5NaHXwInGOlRX2rYM300aoJSRuBB', // client
//    api_url: 'https://staging.tikisites.com/api',
//    site_addr: 'https://staging.tikisites.com',
//    site_address: 'https://staging.tikisites.com/',
//  };

// Local
export const environment = {
 production: false,
 stripe_sk: 'sk_test_yzdkYc6t9GzoDMEfTeNBcDuG00rVcpv242',
 stripe_pk: 'pk_test_DSSTxP5NaHXwInGOlRX2rYM300aoJSRuBB', // client
 site_addr: 'http://localhost/tikisites',
 api_url: 'http://localhost/tikisites/api',
 site_address: 'http://localhost/tikisites/',
};
