using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using System.Windows.Forms;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.ListView;
using static System.Windows.Forms.VisualStyles.VisualStyleElement.StartPanel;

namespace asm2_attendance
{
    public partial class Users : Form
    {
        SqlConnectionStringBuilder sqlstrbuilder = new SqlConnectionStringBuilder();
        public Users()
        {
            //Data Source=Cuong\CUONGDUA;Initial Catalog=Attendances;Integrated Security=True;Trust Server Certificate=True
            //Data Source=LAPTOP-DED5LD2D\SQLEXPRESS;Initial Catalog=Attendance;Integrated Security=True;Trust Server Certificate=True
            InitializeComponent();
            sqlstrbuilder["Server"] = "Cuong\\CUONGDUA";
            sqlstrbuilder["Initial Catalog"] = "Attendances";
            sqlstrbuilder["Integrated Security"] = "True";
            sqlstrbuilder["Trusted_Connection"] = "Yes";
        }

        private void Users_Load(object sender, EventArgs e)
        {
            txtuserid.Text = "";
            LoadData();
            
        }
        private SqlConnection GetConnection()
        {
            string sql = sqlstrbuilder.ToString();
            var Sqlcon = new SqlConnection(sql);
            try
            {
                if (Sqlcon.State == ConnectionState.Closed)
                {
                    Sqlcon.Open();
                    //MessageBox.Show("Kết Nối Thành Công");
                }
                //LoadData();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error occurred: " + ex.Message);
            }
            finally
            {
                Sqlcon.Close();
            }
            return Sqlcon;
        }
        private void LoadData()
        {
            string sql = sqlstrbuilder.ToString();
            var Sqlcon = new SqlConnection(sql);
            Sqlcon.Open();
            SqlCommand cmd = new SqlCommand();
            cmd.Connection = Sqlcon;
            cmd.CommandText = "SELECT * FROM Users";
            var sqlreader = cmd.ExecuteReader();
            
            var datatable = new DataTable();
            datatable.Load(sqlreader);
            dataGridView1.DataSource = datatable;

            Sqlcon.Close();
        }
        //public void TestPasswordLength()
        //{
        //    // Giá trị biên thấp nhất (Minimum Boundary Value)
        //    string minPassword = "123456"; // 6 ký tự, hợp lệ
        //    bool resultMin = ValidatePassword(minPassword);
        //    Console.WriteLine("Password: {0} - Expected: True, Actual: {1}", minPassword, resultMin);

        //    // Dưới giá trị biên thấp nhất (Below Minimum Boundary Value)
        //    string belowMinPassword = "12345"; // 5 ký tự, không hợp lệ
        //    bool resultBelowMin = ValidatePassword(belowMinPassword);
        //    Console.WriteLine("Password: {0} - Expected: False, Actual: {1}", belowMinPassword, resultBelowMin);

        //    // Giá trị biên cao nhất (Maximum Boundary Value)
        //    string maxPassword = "123456789012"; // 12 ký tự, hợp lệ
        //    bool resultMax = ValidatePassword(maxPassword);
        //    Console.WriteLine("Password: {0} - Expected: True, Actual: {1}", maxPassword, resultMax);

        //    // Trên giá trị biên cao nhất (Above Maximum Boundary Value)
        //    string aboveMaxPassword = "1234567890123"; // 13 ký tự, không hợp lệ
        //    bool resultAboveMax = ValidatePassword(aboveMaxPassword);
        //    Console.WriteLine("Password: {0} - Expected: False, Actual: {1}", aboveMaxPassword, resultAboveMax);
        //}

        private bool IsUserNameExist(string userName)
        {
            SqlConnection sqlConnection = GetConnection();
            sqlConnection.Open();
            SqlCommand cmd = new SqlCommand();
            cmd.Connection = sqlConnection;
            cmd.CommandText = "SELECT COUNT(1) FROM Users WHERE UserName = @UserName";
            cmd.Parameters.AddWithValue("@UserName", userName);
            int count = (int)cmd.ExecuteScalar();
            return count > 0;  
            
        }
        private void btnadd_Click(object sender, EventArgs e)
        {
            string phone = txtphone.Text;
            //int userID = Convert.ToInt32(txtuserid.Text);
            //if (userID > 0)
            //{
            string username = txtusername.Text;
                string password = txtpass.Text;
                string firstname = txtfirstname.Text;
                string lastname = txtlastname.Text;
                
                string gender = "";
                string dob = dateTimePicker1.Text;
                string role = "";
            //bool resultAboveMax = ValidatePassword(password);
            if (IsUserNameExist(username))
            {
                MessageBox.Show("UserName already exists, please choose another name.");
                return;
            }
            if (rbtnfemale.Checked)
                {
                gender = "female";
                }
                else
                {
                gender = "male";
                }
                if (rbtnadmin.Checked)
                {
                    role = "admin";
                }
                else if (rbtnteacher.Checked)
                {
                    role = "teacher";
                }
                else
                {
                    role = "student";
                }
                if (string.IsNullOrWhiteSpace(username))
                {
                MessageBox.Show("Username cannot be blank.");
                    
                }
                //else if (Regex.IsMatch(username, @"^[^@\s]+@[^@\s]+\.[^@\s]+$"))
                //{
                //    MessageBox.Show("Username cannot be blank.");
                //}
                else
                {
                    if (password.Length < 8)
                    {
                        MessageBox.Show("Password must have at least 8 characters.");

                    }
                    else
                    {
                        SqlConnection sqlConnection = GetConnection();
                        sqlConnection.Open();
                        if (sqlConnection.State == ConnectionState.Open)
                        {
                            SqlCommand cmd = new SqlCommand();
                            cmd.Connection = sqlConnection;
                            cmd.CommandText = "INSERT INTO Users VALUES (@username , @password , @role , @gender , @phone , @firstname , @lastname  , @dob )";
                            cmd.Parameters.AddWithValue("username", username);
                            cmd.Parameters.AddWithValue("gender", gender);
                            cmd.Parameters.AddWithValue("password", password);
                            cmd.Parameters.AddWithValue("firstname", firstname);
                            cmd.Parameters.AddWithValue("lastname", lastname);
                            cmd.Parameters.AddWithValue("phone", phone);
                            cmd.Parameters.Add("dob", SqlDbType.Date).Value = dob;
                            cmd.Parameters.AddWithValue("role", role);

                            var result = cmd.ExecuteNonQuery();
                            if (result == 0)
                            {
                                MessageBox.Show("Unable to add user");
                            }
                            else
                            {
                                MessageBox.Show("Added");
                                LoadData();
                            }
                        }
                        sqlConnection.Close();
                    }
                }
            }

        //}
        public bool ValidatePassword(string phone)
        {
            // Giả sử điều kiện hợp lệ là mật khẩu phải từ 6 đến 12 ký tự
            if (phone.Length ==10 )
            {
                return true;
            }
            return false;
        }
        private void btnedit_Click(object sender, EventArgs e)
        {

            int userID = Convert.ToInt32(txtuserid.Text);
            if (userID > 0)
            {
                string gender = string.Empty;
                string username = txtusername.Text;
                string password = txtpass.Text;
                string firstname = txtfirstname.Text;
                string lastname = txtlastname.Text;
                string phone = txtphone.Text;
                string dob = dateTimePicker1.Text;
                string role = "";
                if (rbtnfemale.Checked)
                {
                    gender = "female";
                }
                else
                {
                    gender = "male";
                }
                if (rbtnadmin.Checked)
                {
                    role = "admin";
                }
                else if (rbtnteacher.Checked)
                {
                    role = "teacher";
                }
                else
                {
                    role = "student";
                }
                if (string.IsNullOrWhiteSpace(username))
                {
                    MessageBox.Show("Username cannot be blank.");

                }
                else
                {
                    if (password.Length < 8)
                    {
                        MessageBox.Show("Password must have at least 8 characters.");

                    }
                    else
                    {
                        SqlConnection sqlConnection = GetConnection();
                        sqlConnection.Open();
                        if (sqlConnection.State == ConnectionState.Open)
                        {
                            SqlCommand cmd = new SqlCommand();
                            cmd.Connection = sqlConnection;
                            cmd.CommandText = "UPDATE Users SET " +
                                                 "username = @username , " +
                                                 "password = @password , " +
                                                 "lastname = @lastname , " +
                                                 "firstname = @firstname , " +
                                                 "phone = @phone , " +
                                                 "role = @role , " +
                                                 "dateofbirth = @dob , " +
                                                 "gender = @gender " +
                                                 "WHERE UserID = @UserID";
                                                                                                                                                     cmd.Parameters.AddWithValue("UserID", userID);
                            cmd.Parameters.AddWithValue("username", username);
                            cmd.Parameters.AddWithValue("gender", gender);
                            cmd.Parameters.AddWithValue("password", password);
                            cmd.Parameters.AddWithValue("firstname", firstname);
                            cmd.Parameters.AddWithValue("lastname", lastname);
                            cmd.Parameters.AddWithValue("phone", phone);
                            cmd.Parameters.Add("dob", SqlDbType.Date).Value = dob;
                            cmd.Parameters.AddWithValue("role", role);

                            var result = cmd.ExecuteNonQuery();
                            if (result == 0)
                            {
                                MessageBox.Show("Unable to update user");
                            }
                            else
                            {
                                MessageBox.Show("Updated");
                                LoadData();
                            }
                        }
                        sqlConnection.Close();
                    }
                }
            }
        }

        private void btndelect_Click(object sender, EventArgs e)
        {
            int userID = Convert.ToInt32(txtuserid.Text);
            if (userID > 0)
            {
               
                SqlConnection sqlConnection = GetConnection();
                sqlConnection.Open();
                if (sqlConnection.State == ConnectionState.Open)
                {
                    SqlCommand cmd = new SqlCommand();
                    cmd.Connection = sqlConnection;
                    cmd.CommandText = "DELETE FROM Users WHERE UserID = @UserID ";
                    cmd.Parameters.AddWithValue("UserID", userID);

                    var result = cmd.ExecuteNonQuery();
                    if (result == 0)
                    {
                        MessageBox.Show("User cannot be deleted");
                    }
                    else
                    {
                        MessageBox.Show("Deleted");
                        LoadData();
                    }
                }
                sqlConnection.Close();
            }
        }

        private void dataGridView1_CellClick(object sender, DataGridViewCellEventArgs e)
        {
            int index = dataGridView1.SelectedCells[0].RowIndex;
            int userID = Convert.ToInt32(dataGridView1.Rows[index].Cells[0].Value);
            txtuserid.Text = userID.ToString();
            txtfirstname.Text = Convert.ToString(dataGridView1.Rows[index].Cells[6].Value);
            txtlastname.Text = Convert.ToString(dataGridView1.Rows[index].Cells[7].Value);
            txtusername.Text = Convert.ToString(dataGridView1.Rows[index].Cells[1].Value);
            txtpass.Text = Convert.ToString(dataGridView1.Rows[index].Cells[2].Value);
            txtphone.Text = Convert.ToString(dataGridView1.Rows[index].Cells[5].Value);
            dateTimePicker1.Text = Convert.ToString(dataGridView1.Rows[index].Cells[8].Value);
            if (Convert.ToString(dataGridView1.Rows[index].Cells[4].Value) == "female")
            {
                rbtnfemale.Checked = true;
            }
            else if (Convert.ToString(dataGridView1.Rows[index].Cells[4].Value) == "male")
            {
                rbtnmale.Checked = true;
            }
            if (Convert.ToString(dataGridView1.Rows[index].Cells[3].Value) == "admin")
            {
                rbtnadmin.Checked = true;
            }
            else if (Convert.ToString(dataGridView1.Rows[index].Cells[3].Value) == "teacher")
            {
                rbtnteacher.Checked = true;
            }
            else
            {
                rbtnstudent.Checked = true;
            }
        }

        private void label9_Click(object sender, EventArgs e)
        {

        }

        private void btnexit_Click(object sender, EventArgs e)
        {

            DialogResult result = MessageBox.Show("Are you sure you want to exit?", "Confirm exit", MessageBoxButtons.YesNo, MessageBoxIcon.Question);

            if (result == DialogResult.Yes)
            {
                //    Login form2 = new Login();
                //    form2.Show();
                //    this.Close();
                this.Hide();

                Login formlogin = new Login();
                formlogin.ShowDialog();

                this.Close();
            }
        }

        private void rbtnstudent_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void rbtnteacher_CheckedChanged(object sender, EventArgs e)
        {

        }

        private void txtuserid_Click(object sender, EventArgs e)
        {

        }

        private void label6_Click(object sender, EventArgs e)
        {

        }

        private void dateTimePicker1_ValueChanged(object sender, EventArgs e)
        {

        }
    }
}
