package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.firebase.ui.database.FirebaseListOptions;
import com.google.firebase.database.Query;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderModels.ChatMessage;

import com.firebase.ui.database.FirebaseListAdapter;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;

import org.joda.time.DateTime;
import org.joda.time.Days;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

/**
 * Created by Nabeel on 1/15/2018.
 */

public class RChatFragment extends Fragment {

    ImageView send_icon;
    private FirebaseListAdapter<ChatMessage> adapter;
    SharedPreferences chatPref;
    String sender_id,receiver_id,name;
    private static SimpleDateFormat DATE_FORMAT = new SimpleDateFormat("MMM dd, yyyy");
    private static SimpleDateFormat TIME_FORMAT = new SimpleDateFormat(" 'at' h:mm aa");
    ListView listOfMessages;
    DatabaseReference mDatabase;
    String time_start,time_end;
    int today_day;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_ADJUST_PAN);
        View v = inflater.inflate(R.layout.rider_chat_fragment, container, false);
        chatPref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        sender_id = chatPref.getString(PreferenceClass.pre_user_id,"");
        receiver_id = chatPref.getString(PreferenceClass.ADMIN_USER_ID,"0");

        receiver_id="0";

        time_start = "11:59:00";
        time_end="23:59:00";

        Calendar cal = Calendar.getInstance();
        today_day = cal.get(Calendar.DAY_OF_MONTH);

        initUI(v);
        displayChatMessages();
        return v;
    }

    public void initUI(final View v){

        String f_name = chatPref.getString(PreferenceClass.pre_first,"");
        String l_name = chatPref.getString(PreferenceClass.pre_last,"");
        final String user_name = f_name+ " "+ l_name;

        listOfMessages = (ListView) v.findViewById(R.id.list_of_messages);

        final FirebaseDatabase firebaseDatabase = FirebaseDatabase.getInstance();
        mDatabase = firebaseDatabase.getReference().child("Chat").child(sender_id+"-"+receiver_id);

        send_icon = v.findViewById(R.id.send_icon);

        send_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                EditText input = (EditText)v.findViewById(R.id.input);

                // Read the input field and push a new instance
                // of ChatMessage to the Firebase database
                if(input.getText().toString().isEmpty()){
                    Toast.makeText(getContext(),"Add Character to Send.",Toast.LENGTH_SHORT).show();
                }
                else {
                    mDatabase.push().setValue(new ChatMessage(receiver_id,
                            sender_id,user_name,input.getText().toString()));
                }

                // Clear the input
                input.setText("");

            }
        });

    }
    @SuppressWarnings("deprecation")
    public void displayChatMessages() {

        Query query = FirebaseDatabase.getInstance().getReference().child("Chat").child(sender_id+"-"+receiver_id);
//The error said the constructor expected FirebaseListOptions - here you create them:
        FirebaseListOptions<ChatMessage> options = new FirebaseListOptions.Builder<ChatMessage>()
                .setQuery(query, ChatMessage.class)
                .setLayout(R.layout.row_item_chat_list)
                .build();


        adapter = new FirebaseListAdapter<ChatMessage>(options) {
            @Override
            protected void populateView(View v, ChatMessage model, int position) {
                // Get references to the views of message.xml

                ChatMessage getDataAdapter1 =  adapter.getItem(position);

                TextView messageTime = (TextView) v.findViewById(R.id.message_time);
                messageTime.setText( ChangeDate(getDataAdapter1.getTimestamp()));
                RelativeLayout bubble_admin = (RelativeLayout)v.findViewById(R.id.bubble_admin);
                RelativeLayout bubble_rider = (RelativeLayout)v.findViewById(R.id.bubble);



              /*  Date startDate = new Date(getDataAdapter1.getTimestamp());
                Date endDate = new Date(getDataAdapter1.getTimestamp());
                int startminutes = startDate.getMinutes();
                int endminutes = endDate.getMinutes();

                int difference = endminutes-startminutes;

                if(difference>=1){
                    messageTime.setVisibility(View.VISIBLE);
                }
                else {
                    messageTime.setVisibility(View.GONE);
                }
*/
                /// Check Message Time

/*
                SimpleDateFormat sdf = new SimpleDateFormat("yyyy-mm-dd HH:MM");
                Date testDate = null;
                try {
                    testDate = sdf.parse(timeStartEnd);
                }catch(Exception ex){
                    ex.printStackTrace();
                }

                try {
                    SimpleDateFormat simpleDateFormat = new SimpleDateFormat("HH:mm");
                    String newFormat = simpleDateFormat.format(testDate);
                    Date date1 = simpleDateFormat.parse(newFormat);
                    //Date date2 = simpleDateFormat.parse(time_end);

                    long difference = date1.getTime() - date1.getTime();
                    long days = (int) (difference / (1000*60*60*24));
                    long hours_ = (int) ((difference - (1000*60*60*24*days)) / (1000*60*60));
                    hours_ = (hours_ < 0 ? -hours_ : hours_);

                    if(hours_>1){
                        messageTime.setVisibility(View.VISIBLE);
                    }
                    else {
                        messageTime.setVisibility(View.GONE);
                    }


                } catch (ParseException e) {
                    e.printStackTrace();
                }*/

                // Format the date before showing it

                bubble_admin.setTag(getDataAdapter1);
                ChatMessage chatMessage=(ChatMessage)bubble_admin.getTag();

                String senderID = model.getSender_id();

                if(senderID!=null) {
                    if (!chatMessage.getSender_id().equalsIgnoreCase(sender_id)) {

                        bubble_admin.setVisibility(View.VISIBLE);
                        bubble_rider.setVisibility(View.GONE);

                    /*    //RelativeLayout.LayoutParams params = (RelativeLayout.LayoutParams) bubble.getLayoutParams();
                        RelativeLayout.LayoutParams layout_description = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.WRAP_CONTENT,
                                RelativeLayout.LayoutParams.WRAP_CONTENT);

                        layout_description.setMargins(20,20,20,0);
                        layout_description.addRule(RelativeLayout.ALIGN_PARENT_LEFT);

                        bubble.setLayoutParams(layout_description);

                        bubble.setBackgroundResource(R.drawable.bubble_admin);*/

                        TextView messageText = (TextView) v.findViewById(R.id.message_text_admin);
                        messageText.setTextColor(getResources().getColor(R.color.black));

                        messageText.setText(model.getText());

                    } else {
                        bubble_admin.setVisibility(View.GONE);
                        bubble_rider.setVisibility(View.VISIBLE);
                        TextView messageText = (TextView) v.findViewById(R.id.message_text);
                        messageText.setText(model.getText());

                    }
                }

            }
        };
        listOfMessages.setAdapter(adapter);
        listOfMessages.setTranscriptMode(ListView.TRANSCRIPT_MODE_ALWAYS_SCROLL);

    }


    public String ChangeDate(String date){

        try {

            //current date in millisecond
            long currenttime = System.currentTimeMillis();

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
            long difference = currenttime - databasedate;
            if (difference < 86400000) {
                int chatday = Integer.parseInt(date.substring(0, 2));
                SimpleDateFormat sdf = new SimpleDateFormat("hh:mm a");
                if (today_day == chatday)
                    return "Today " + sdf.format(d);
                else if ((today_day - chatday) == 1)
                    return "Yesterday " + sdf.format(d);
            } else if (difference < 172800000) {
                int chatday = Integer.parseInt(date.substring(0, 2));
                SimpleDateFormat sdf = new SimpleDateFormat("hh:mm a");
                if ((today_day - chatday) == 1)
                    return "Yesterday " + sdf.format(d);
            }

            SimpleDateFormat sdf = new SimpleDateFormat("MMM-dd-yyyy hh:mm a");
            return sdf.format(d);
        }
        catch (Exception e){

        }
        finally {

            return "";
        }
    }


    @Override
    public void onStart() {
        super.onStart();
        adapter.startListening();
    }
    @Override
    public void onStop() {
        super.onStop();
        adapter.stopListening();
    }
    public static String getRelativeDateTimeString(Calendar startDateCalendar) {
        if (startDateCalendar == null) return null;

        DateTime startDate = new DateTime(startDateCalendar.getTimeInMillis());
        DateTime today = new DateTime();
        int days = Days.daysBetween(today.withTimeAtStartOfDay(), startDate.withTimeAtStartOfDay()).getDays();

        String date;
        switch (days) {
            case -1: date = "Yesterday"; break;
            case 0: date = "Today"; break;
            case 1: date = "Tomorrow"; break;
            default: date = DATE_FORMAT.format(startDateCalendar.getTime()); break;
        }
        String time = TIME_FORMAT.format(startDateCalendar.getTime());
        return date + time;
    }



    public static String getTimeDuration(String StartTime24, String EndTime24) {
        String duration = "";

        try {
            SimpleDateFormat parseFormat = new SimpleDateFormat("HH:mm");
            Date startTime = parseFormat.parse(StartTime24);
            Date endTime = parseFormat.parse(EndTime24);

            long mills = endTime.getTime() - startTime.getTime();
            long minutes = mills / (1000 * 60);

            duration = ""+ minutes ;
        } catch (ParseException ex) {
            // exception handling here
        }

        return duration;

    }

    private boolean checktimings(String time, String endtime) {

        String pattern = "HH:mm";
        SimpleDateFormat sdf = new SimpleDateFormat(pattern);

        try {
            Date date1 = sdf.parse(time);
            Date date2 = sdf.parse(endtime);

            if(date1.before(date2)) {
                return true;
            } else {

                return false;
            }
        } catch (ParseException e){
            e.printStackTrace();
        }
        return false;
    }

    public static String convertSecondsToHMmSs(long seconds) {
        long s = seconds % 60;
        long m = (seconds / 60) % 60;
        long h = (seconds / (60 * 60)) % 24;
        return String.format("%d:%02d:%02d", h,m,s);
    }
}
