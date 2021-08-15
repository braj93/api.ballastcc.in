import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

import { ApiService } from './api.service';
import { User } from '../models';
import { map } from 'rxjs/operators';

@Injectable()
export class CampaignService {
  public copy_values: any;
  public campaign_tabs: any = [
    {
      'tilte': 'Basic Details',
      'value': 'create',
      'url': '/user/campaigns/create',
      'active': false,
      'is_tab': true
    },
    {
      'tilte': 'Call Tracking Setup',
      'value': 'calltracking',
      'url': '',
      'active': false,
      'is_tab': false
    },
    {
      'tilte': 'Landing Page Setup',
      'value': 'landingpage',
      'url': '',
      'active': false,
      'is_tab': false
    },
    {
      'tilte': 'QR Code Setup',
      'value': 'qrcode',
      'url': '',
      'active': false,
      'is_tab': false
    }
  ];

  public simple_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Media'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum Sue da',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz,'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'contact_us': {
      'title': {
        'value': 'Get your free trial now!',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
      },
      'description': {
        'type': 'text',
        'value': 'Or Call Us Now'
      },
      'number': {
        'type': 'phone',
        'value': '5555555555'
      },
    },
  }

  public detailed_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Header Title'
      },
      'number': {
        'type': 'phone',
        'value': '9999999999'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum Sue da',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz,'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },
    'contact_us': {
      'title': {
        'value': 'Get your free trial now!',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
      },
      'description': {
        'type': 'text',
        'value': 'Or Call Us Now'
      },
      'number': {
        'type': 'phone',
        'value': '5555555555'
      },
    },
    'two_section_component': {
      'title': {
        'value': 'Title',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'Paragraph Title',
          'type': 'text',
        },
        'description': {
          'value': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry(s) standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen dummy book.',
          'type': 'text_area',
        }
      },
      'right': {
        'title': {
          'value': 'Paragraph Title',
          'type': 'text',
        },
        'description': {
          'value': 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry(s) standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen dummy book.',
          'type': 'text_area',
        }
      }
    },
  }

  public media_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Media'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum Sue da',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz,'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'contact_us': {
      'title': {
        'value': 'Subscribe Now',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
      },
    },
    'featured': {
      'title': {
        'value': 'Section Title',
        'type': 'text',
      },
      'sub_title': {
        'value': 'Featured news',
        'type': 'text',
      },
      'description': {
        'type': 'text_area',
        'value': 'Lorem ipsum dolor sit amet,consectetur adipiscing elit.'
      },
      'cta': {
        'type': 'link',
        'value': 'Learn more',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },
    'feeds': {
      'title': {
        'value': 'Latest Feed',
        'type': 'text',
      },
      'one': {
        'category': {
          'value': 'Category One',
          'type': 'text'
        },
        'title': {
          'type': 'text',
          'value': 'Lorem ipsum One'
        }
      },
      'two': {
        'category': {
          'value': 'Category Two',
          'type': 'text'
        },
        'title': {
          'type': 'text',
          'value': 'Lorem ipsum Two'
        }
      },
      'three': {
        'category': {
          'value': 'Category Three',
          'type': 'text'
        },
        'title': {
          'type': 'text',
          'value': 'Lorem ipsum Three'
        }
      },
      'four': {
        'category': {
          'value': 'Category Four',
          'type': 'text'
        },
        'title': {
          'type': 'text',
          'value': 'Lorem ipsum Four'
        }
      },
      'five': {
        'category': {
          'value': 'Category Five',
          'type': 'text'
        },
        'title': {
          'type': 'text',
          'value': 'Lorem ipsum Five'
        }
      },
      'six': {
        'category': {
          'value': 'Category Six',
          'type': 'text'
        },
        'title': {
          'type': 'text',
          'value': 'Lorem ipsum Six'
        }
      }
    },
  }
  public restaurant_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Restaurant'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum sue amer',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog.'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA Link',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'aboutus_component': {
      'title': {
        'type': 'text',
        'value': 'About us'
      },
      'description': {
        'type': 'text_editor',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'two_section_component': {
      'title': {
        'value': 'HOUSE FOR SALE',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'House Name One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'right': {
        'title': {
          'value': 'House Name Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'contact_us': {
      'title': {
        'value': 'Subscribe Now',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
        // 'type':'button',
      },
    },
    'five_column_content_promo': {
      'title': {
        'value': 'Section Title',
        'type': 'text',
      },
      'one': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'two': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'three': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'four': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'five': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },
  }
  public events_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Events'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'concert': {
      'title': {
        'type': 'text',
        'value': 'Concert',
      },
      'concert_one': {
        'title': {
          'type': 'text',
          'value': 'concert One',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'concert_two': {
        'title': {
          'type': 'text',
          'value': 'concert Two',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'concert_three': {
        'title': {
          'type': 'text',
          'value': 'concert Three',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
    },


    'event': {
      'title': {
        'type': 'text',
        'value': 'Event',
      },
      'event_one': {
        'title': {
          'type': 'text',
          'value': 'Event One',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'event_two': {
        'title': {
          'type': 'text',
          'value': 'Event Two',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'event_three': {
        'title': {
          'type': 'text',
          'value': 'Event Three',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'video': {
      'title': {
        'type': 'text',
        'value': 'Look inside',
      },
      'sub_title': {
        'type': 'text',
        'value': 'Live Concert Promo',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz.'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA Link',
        'link': 'https://www.youtube.com/watch?v=9xwazD5SyVg',
        'target': '_blank'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum sue amer',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz.'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA Link',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'aboutus_component': {
      'title': {
        'type': 'text',
        'value': 'WHY CHOOSE US'
      },
      'description': {
        'type': 'text_editor',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'two_section_component': {
      'title': {
        'value': 'HOUSE FOR SALE',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'House Name One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'right': {
        'title': {
          'value': 'House Name Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'contact_us': {
      'title': {
        'value': 'Subscribe Now',
        'type': 'text',
      },
      'button': {
        'value': 'Submit',
        'type': 'text',
        // 'type':'button',
      },
    },
    'three_column_content_promo': {
      'title': {
        'value': 'Section Title',
        'type': 'text',
      },
      'cta': {
        'type': 'link',
        'value': 'LAST LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      },
      'one': {
        'title': {
          'value': 'A tribute for our fans.',
          'type': 'text',
        },
        'category': {
          'value': 'Category One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'two': {
        'title': {
          'value': 'A tribute for our fans.',
          'type': 'text',
        },
        'category': {
          'value': 'Category Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'three': {
        'title': {
          'value': 'A tribute for our fans.',
          'type': 'text',
        },
        'category': {
          'value': 'Category Three',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },
  }

  public automotive_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Automotive'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Contact us to get the best pricing.',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz.'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'aboutus_component': {
      'title': {
        'type': 'text',
        'value': 'WHY CHOOSE US'
      },
      'description': {
        'type': 'text_editor',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'imgae_text_two_section_component': {
      'title': {
        'type': 'text',
        'value': 'Product One'
      },
      'description': {
        'type': 'text_area',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'imgae_text_two_section_component_two': {
      'title': {
        'type': 'text',
        'value': 'Product Two'
      },
      'description': {
        'type': 'text_area',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'two_section_component': {
      'title': {
        'value': 'HOUSE FOR SALE',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'House Name One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'right': {
        'title': {
          'value': 'House Name Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'contact_us': {
      'title': {
        'value': 'Contact Us',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
        // 'type':'button',
      },
    },
    'three_column_content_promo': {
      'title': {
        'value': 'our programs',
        'type': 'text',
      },
      'cta': {
        'type': 'link',
        'value': 'LAST LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      },
      'one': {
        'title': {
          'value': 'Program One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'two': {
        'title': {
          'value': 'Program Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'three': {
        'title': {
          'value': 'Program Three',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },
  }

  public retail_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Retail Shop'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Contact us to get the best pricing.',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz.'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'aboutus_component': {
      'title': {
        'type': 'text',
        'value': 'WHY CHOOSE US'
      },
      'description': {
        'type': 'text_editor',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'imgae_text_two_section_component': {
      'title': {
        'type': 'text',
        'value': 'Product One'
      },
      'description': {
        'type': 'text_area',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'two_section_component': {
      'title': {
        'value': 'HOUSE FOR SALE',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'House Name One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'right': {
        'title': {
          'value': 'House Name Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'contact_us': {
      'title': {
        'value': 'SAY HELLO',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
        // 'type':'button',
      },
    },
    'three_column_content_promo': {
      'title': {
        'value': 'our programs',
        'type': 'text',
      },
      'cta': {
        'type': 'link',
        'value': 'LAST LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      },
      'one': {
        'title': {
          'value': 'Program One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'two': {
        'title': {
          'value': 'Program Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'three': {
        'title': {
          'value': 'Program Three',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },
  }

  public fitness_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Fitness/Gym'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum Sue da',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz,'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'aboutus_component': {
      'title': {
        'type': 'text',
        'value': 'WHY CHOOSE US'
      },
      'description': {
        'type': 'text_editor',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate nulla pariatur.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate nulla pariatur.Lorem ipsum dolor sit amet, consectetur adipiscing elit Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim veniam quis nostrud exercitation ullamco.laboris nisi ut aliquip ex ea commodo consequat.'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'two_section_component': {
      'title': {
        'value': 'HOUSE FOR SALE',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'House Name One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'right': {
        'title': {
          'value': 'House Name Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'contact_us': {
      'title': {
        'value': 'SAY HELLO',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
        // 'type':'button',
      },
    },
    'three_column_content_promo': {
      'title': {
        'value': 'our programs',
        'type': 'text',
      },
      'cta': {
        'type': 'link',
        'value': 'LAST LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      },
      'one': {
        'title': {
          'value': 'Program One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'two': {
        'title': {
          'value': 'Program Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'three': {
        'title': {
          'value': 'Program Three',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },
  }

  public real_state_values: any = {
    'header': {
      'title': {
        'type': 'text',
        'value': 'Real Estate'
      },
      'number': {
        'type': 'phone',
        'value': '1234567890'
      }
    },
    'banner_background_image': {
      'image_id': '',
      'image_url': '',
      'alt_tag': '',
      'title': '',
    },
    'banner': {
      'title': {
        'type': 'text',
        'value': 'Lorem ipsum Sue da',
      },
      'description': {
        'type': 'text_area',
        'value': 'The quick, brown fox jumps over a lazy dog. DJs flock by when MTV ax quiz prog. Junk MTV quiz graced by fox whelps. Bawds jog, flick quartz,'
      },
      'cta': {
        'type': 'link',
        'value': 'CTA LINK',
        'link': 'https://platform.marketingtiki.com',
        'target': '_blank'
      }
    },

    'aboutus_component': {
      'title': {
        'type': 'text',
        'value': 'About us'
      },
      'description': {
        'type': 'text_area',
        'value': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
      },
      'image': {
        'image_id': '',
        'image_url': '',
        'alt_tag': '',
        'title': '',
      }
    },

    'two_section_component': {
      'title': {
        'value': 'HOUSE FOR SALE',
        'type': 'text',
      },
      'left': {
        'title': {
          'value': 'House Name One',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'right': {
        'title': {
          'value': 'House Name Two',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },

    'contact_us': {
      'title': {
        'value': 'SAY HELLO',
        'type': 'text',
      },
      'button': {
        'value': 'Send',
        'type': 'text',
        // 'type':'button',
      },
    },
    'three_column_content_promo': {
      'title': {
        'value': 'CLIENTS',
        'type': 'text',
      },
      'one': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'two': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      },
      'three': {
        'title': {
          'value': 'HOUSE FOR SALE',
          'type': 'text',
        },
        'image': {
          'image_id': '',
          'image_url': '',
          'alt_tag': '',
          'title': '',
        }
      }
    },
  }
  constructor(
    private apiService: ApiService,
  ) { }

  // GET CampaignIdList'S LIST
  // getCampaignByIdList(input: any): Observable<User> {
  //   return this.apiService.post('/campaign/campaign_list', input)
  //     .pipe(map(
  //       data => {
  //         return data;
  //       }
  //     ));
  // }

  update_template_default_values(template_guid, type) {
    let default_values = '';
    if (type === 'template_real_estate') {
      default_values = JSON.stringify(this.real_state_values);
    } else if (type === 'template_fitness') {
      default_values = JSON.stringify(this.fitness_values);
    } else if (type === 'template_retail') {
      default_values = JSON.stringify(this.retail_values);
    } else if (type === 'template_restaurant') {
      default_values = JSON.stringify(this.restaurant_values);
    } else if (type === 'template_event') {
      default_values = JSON.stringify(this.events_values);
    } else if (type === 'template_automotive') {
      default_values = JSON.stringify(this.automotive_values);
    } else if (type === 'template_media') {
      default_values = JSON.stringify(this.media_values);
    } else if (type === 'template_simple') {
      default_values = JSON.stringify(this.simple_values);
    } else if (type === 'template_detailed') {
      default_values = JSON.stringify(this.detailed_values);
    }
    if (default_values) {
      return this.apiService.post('/campaign/update_template_default_values', { template_id: template_guid, default_values: default_values })
        .pipe(map(
          data => {
            return data;
          }
        ));
    }
  }

  // ADD CAMPAIGN
  addCampaign(input: any): Observable<User> {
    return this.apiService.post('/campaign/add_campaign', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  createCallTrackingNumber(input: any): Observable<User> {
    return this.apiService.post('/campaign/create_call_tracking_number', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  updateTrackingNumber(input: any): Observable<User> {
    return this.apiService.post('/campaign/update_tracking_number', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // EDIT CAMPAIGN
  editCampaign(input: any): Observable<User> {
    return this.apiService.post('/campaign/edit_campaign', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET DETAILS CAMPAIGN
  getDetailsById(input: any): Observable<User> {
    return this.apiService.post('/campaign/get_details_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getTrackerDetails(input: any): Observable<User> {
    return this.apiService.post('/campaign/get_tracker_details', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET DETAILS CAMPAIGN
  getCampaignDetailsById(input: any): Observable<User> {
    return this.apiService.post('/campaign/get_campaign_details_by_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getCampaignReportDetailsById(input: any): Observable<User> {
    return this.apiService.post('/campaign/get_campaign_report_details', input)
      .pipe(map(
        data => {
          return data.data.data;
        }
      ));
  }

  // GET TEMPLATE DETAILS BY CAMPAIGN ID
  getTemplateDetailsByCampaignId(input: any): Observable<User> {
    return this.apiService.post('/campaign/get_template_details_by_campaign_id', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET CAMPAIGN LIST
  getCampaignList(input: any): Observable<User> {
    return this.apiService.post('/campaign/campaign_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET TEMPLATES LIST
  getTemplateList(input: any): Observable<User> {
    return this.apiService.post('/campaign/template_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // ADD TEMPLATE
  addCampaignTemplate(input: any): Observable<User> {
    return this.apiService.post('/campaign/add_campaign_template', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // UPDATE TEMPLATE
  updateCampaignTemplate(input: any): Observable<User> {
    return this.apiService.post('/campaign/update_campaign_template', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  postBase64Image(credentials: any): Observable<any> {
    return this.apiService.post('/uploads/upload_base64', credentials)
      .pipe(map((data) => data));
  }

  postBaseImage(credentials: any): Observable<any> {
    return this.apiService.post('/uploads/upload_base64_image', credentials)
      .pipe(map((data) => data));
  }

  updateTemplateDetails(input: any): Observable<User> {
    return this.apiService.post('/campaign/update_template_details', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  updateCampaignSetting(input: any): Observable<User> {
    return this.apiService.post('/campaign/update_campaign_setting', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  generateQrCode(input: any): Observable<User> {
    return this.apiService.post('/campaign/generate_qr_code', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getAdminDashboard(): Observable<User> {
    return this.apiService.get('/campaign/get_admin_dashboard')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  getUserDashboard(): Observable<User> {
    return this.apiService.get('/campaign/get_user_dashboard')
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // GET Landing PageRanking LIST
  getLandingPageRanking(input: any): Observable<User> {
    return this.apiService.post('/campaign/get_landing_page_ranking_list', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }

  // getUniqueString(): Observable<User> {
  //   return this.apiService.get('/campaign/get_unique_string')
  //     .pipe(map(
  //       data => {
  //         return data;
  //       }
  //     ));
  // }

  // getUniqueString(input: any): Observable<User> {
  //   return this.apiService.post('/campaign/get_unique_string', input)
  //     .pipe(map(
  //       data => {
  //         return data;
  //       }
  //     ));
  // }

  updateCampaignStatus(input: any): Observable<User> {
    return this.apiService.post('/campaign/update_campaign_status', input)
      .pipe(map(
        data => {
          return data;
        }
      ));
  }
}
