using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace asm2_attendance
{
    public partial class Login : Form
    {
        SqlConnectionStringBuilder sqlstrbuilder = new SqlConnectionStringBuilder();
        public Login()
        {
            InitializeComponent();
            sqlstrbuilder["Server"] = "Cuong\\CUONGDUA";
            sqlstrbuilder["Database"] = "Attendances";
            sqlstrbuilder["Integrated Security"] = "True";
            sqlstrbuilder["Trusted_Connection"] = "Yes";
        }

        private void Form1_Load(object sender, EventArgs e)
        {

        }

        private void btnclear_Click(object sender, EventArgs e)
        {
            txtpass.Text = "";
            txtuser.Text = "";
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

        private void btnlogin_Click(object sender, EventArgs e)
        {
            
            string password = txtpass.Text;

            SqlConnection sqlConnection = GetConnection();
            sqlConnection.Open();

            SqlCommand cmd = new SqlCommand();
            cmd.Connection = sqlConnection;
            cmd.CommandText = "SELECT Role FROM Users WHERE username = @username AND password = @password";
            string username = txtuser.Text;
            //cmd.CommandText = "SELECT Role FROM Users WHERE username = " +username ;
            cmd.Parameters.AddWithValue("@username", username);
            cmd.Parameters.AddWithValue("@password", password);

            try
            {
                // Mở kết nối
                

                // Thực thi câu lệnh SQL và lấy vai trò của người dùng
                string role = (string)cmd.ExecuteScalar();

                if (!string.IsNullOrEmpty(role))
                {
                    //Nếu vai trò được xác định, mở form tương ứng
                    MessageBox.Show("Login success!");

                    switch (role)
                    {
                        case "student":
                            this.Hide();
                            Courses formStudent = new Courses();
                            formStudent.ShowDialog();
                            break;

                        case "teacher":
                            this.Hide();
                            Teacher formTeacher = new Teacher();
                            formTeacher.ShowDialog();
                            break;

                        case "admin":
                            this.Hide();
                            Admin formAdmin = new Admin();
                            formAdmin.ShowDialog();
                            break;

                        default:
                            MessageBox.Show("Role invaid!");
                            break;
                    }

                    this.Close();

                }
                else
                {
                    MessageBox.Show("Username or password is incorrect.");
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Error occurred: " + ex.Message);
            }
            finally
            {
                sqlConnection.Close();
            }
            
        }

        private void linkLabel2_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {
            this.Hide();
            Users formlogin = new Users();
            formlogin.ShowDialog();
            this.Close();
        }

        private void linkLabel1_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {
            Forgot_pass forgotPasswordForm = new Forgot_pass();
            forgotPasswordForm.Show();
        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {

        }
    }
    
}
