/* global wp */

/**
 * Schedule Unpublish Panel
 */

import ScheduleUnpublish from './scheduleUnpublish';

const {
  plugins: {
    registerPlugin,
  },
} = wp;

registerPlugin('schedule-unpublish', {
  render: ScheduleUnpublish,
});
