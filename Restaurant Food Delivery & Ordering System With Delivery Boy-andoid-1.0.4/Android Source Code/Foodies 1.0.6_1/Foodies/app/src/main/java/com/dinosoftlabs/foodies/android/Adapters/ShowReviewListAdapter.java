package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RatingBar;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.Models.RatingListModel;
import com.dinosoftlabs.foodies.android.R;


import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;


/**
 * Created by Nabeel on 1/25/2018.
 */

public class ShowReviewListAdapter extends RecyclerView.Adapter<ShowReviewListAdapter.ViewHolder> {

    ArrayList<RatingListModel> getDataAdapter;
    Context context;
    OrderAdapter.OnItemClickListner onItemClickListner;
    int today_day;

    public ShowReviewListAdapter(ArrayList<RatingListModel> getDataAdapter, Context context) {
        super();
        this.getDataAdapter = getDataAdapter;
        this.context = context;
    }

    @Override
    public ShowReviewListAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_items_reviews, parent, false);

        ShowReviewListAdapter.ViewHolder viewHolder = new ShowReviewListAdapter.ViewHolder(v);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {
        RatingListModel getDataAdapter1 = getDataAdapter.get(position);

        String f_name = getDataAdapter1.getF_name();
        String l_name = getDataAdapter1.getL_name();

        String date = getDataAdapter1.getCreated();

        holder.reviewer_time_tv.setText(date);

        holder.reviewer_name_tv.setText(f_name+" "+l_name);
        holder.reviewer_desc_tv.setText(getDataAdapter1.getComment());
        holder.ruleRatingBar.setRating(Float.parseFloat(getDataAdapter1.getRating()));

    }

    @Override
    public int getItemCount() {
        return getDataAdapter.size();
    }


    class ViewHolder extends RecyclerView.ViewHolder {

        public TextView reviewer_name_tv, reviewer_desc_tv;
        public TextView reviewer_time_tv;


        RatingBar ruleRatingBar;

        public ViewHolder(View itemView) {

            super(itemView);
            reviewer_name_tv = (TextView) itemView.findViewById(R.id.reviewer_name_tv);
            reviewer_time_tv = (TextView) itemView.findViewById(R.id.reviewer_time_tv);
            reviewer_desc_tv = (TextView) itemView.findViewById(R.id.reviewer_desc_tv);
            ruleRatingBar = itemView.findViewById(R.id.ruleRatingBar);

        }
    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(OrderAdapter.OnItemClickListner onItemClickListner) {
        this.onItemClickListner = onItemClickListner;
    }




    public String ChangeDate(String date){

        //current date in millisecond
        long currenttime= System.currentTimeMillis();

        //database date in millisecond
        SimpleDateFormat f = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss");
        long databasedate = 0;
        Date d = null;
        try {
            d = f.parse(date);
            databasedate = d.getTime();

        } catch (ParseException e) {
            e.printStackTrace();
        }
        long difference=currenttime-databasedate;
        if(difference<86400000){
            int chatday=Integer.parseInt(date.substring(0,2));
            SimpleDateFormat sdf = new SimpleDateFormat("hh:mm a");
            if(today_day==chatday)
                return "Today "+sdf.format(d);
            else if((today_day-chatday)==1)
                return "Yesterday "+sdf.format(d);
        }
        else if(difference<172800000){
            int chatday=Integer.parseInt(date.substring(0,2));
            SimpleDateFormat sdf = new SimpleDateFormat("hh:mm a");
            if((today_day-chatday)==1)
                return "Yesterday "+sdf.format(d);
        }

        SimpleDateFormat sdf = new SimpleDateFormat("MMM-dd-yyyy hh:mm a");
        return sdf.format(d);
    }




    public static String parseDate(String givenDateString) {
        if (givenDateString.equalsIgnoreCase("")) {
            return "";
        }

        long timeInMilliseconds=0;
        SimpleDateFormat sdf = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss");
        try {

            Date mDate = sdf.parse(givenDateString);
            timeInMilliseconds = mDate.getTime();
            System.out.println("Date in milli :: " + timeInMilliseconds);
        } catch (ParseException e) {
            e.printStackTrace();
        }


        String result = "now";
        SimpleDateFormat formatter = new SimpleDateFormat("dd-MM-yyyy hh:mm:ss");

        String todayDate = formatter.format(new Date());
        Calendar calendar = Calendar.getInstance();

        long dayagolong =  timeInMilliseconds;
        calendar.setTimeInMillis(dayagolong);
        String agoformater = formatter.format(calendar.getTime());

        Date CurrentDate = null;
        Date CreateDate = null;

        try {
            CurrentDate = formatter.parse(todayDate);
            CreateDate = formatter.parse(agoformater);

            long different = Math.abs(CurrentDate.getTime() - CreateDate.getTime());

            long secondsInMilli = 1000;
            long minutesInMilli = secondsInMilli * 60;
            long hoursInMilli = minutesInMilli * 60;
            long daysInMilli = hoursInMilli * 24;

            long elapsedDays = different / daysInMilli;
            different = different % daysInMilli;

            long elapsedHours = different / hoursInMilli;
            different = different % hoursInMilli;

            long elapsedMinutes = different / minutesInMilli;
            different = different % minutesInMilli;

            long elapsedSeconds = different / secondsInMilli;

            different = different % secondsInMilli;
            if (elapsedDays == 0) {
                if (elapsedHours == 0) {
                    if (elapsedMinutes == 0) {
                        if (elapsedSeconds < 0) {
                            return "0" + " s";
                        } else {
                            if (elapsedDays > 0 && elapsedSeconds < 59) {
                                return "now";
                            }
                        }
                    } else {
                        return String.valueOf(elapsedMinutes) + "mins ago";
                    }
                } else {
                    return String.valueOf(elapsedHours) + "hr ago";
                }

            } else {
                if (elapsedDays <= 29) {
                    return String.valueOf(elapsedDays) + "d ago";

                }
                else if (elapsedDays > 29 && elapsedDays <= 58) {
                    return "1Mth ago";
                }
                if (elapsedDays > 58 && elapsedDays <= 87) {
                    return "2Mth ago";
                }
                if (elapsedDays > 87 && elapsedDays <= 116) {
                    return "3Mth ago";
                }
                if (elapsedDays > 116 && elapsedDays <= 145) {
                    return "4Mth ago";
                }
                if (elapsedDays > 145 && elapsedDays <= 174) {
                    return "5Mth ago";
                }
                if (elapsedDays > 174 && elapsedDays <= 203) {
                    return "6Mth ago";
                }
                if (elapsedDays > 203 && elapsedDays <= 232) {
                    return "7Mth ago";
                }
                if (elapsedDays > 232 && elapsedDays <= 261) {
                    return "8Mth ago";
                }
                if (elapsedDays > 261 && elapsedDays <= 290) {
                    return "9Mth ago";
                }
                if (elapsedDays > 290 && elapsedDays <= 319) {
                    return "10Mth ago";
                }
                if (elapsedDays > 319 && elapsedDays <= 348) {
                    return "11Mth ago";
                }
                if (elapsedDays > 348 && elapsedDays <= 360) {
                    return "12Mth ago";
                }

                if (elapsedDays > 360 && elapsedDays <= 720) {
                    return "1 year ago";
                }
            }

        } catch (java.text.ParseException e) {
            e.printStackTrace();
        }
        return result;
    }




}
